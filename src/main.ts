// FILE: src/main.ts
import 'reflect-metadata';
import * as dotenv from 'dotenv';
dotenv.config(); // loads .env if present

import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { DocumentBuilder, SwaggerModule } from '@nestjs/swagger';
import { PrismaService } from './prisma.service';
import { BigIntSerializerInterceptor } from './common/interceptors/bigint-serializer.interceptor';
import { ValidationPipe } from '@nestjs/common';

// ---- Safe JSON patches (do NOT change other app behavior) ----
(BigInt.prototype as any).toJSON = function () {
  return this.toString();
};
try {
  // Optional: stringify Prisma Decimal everywhere
  // eslint-disable-next-line @typescript-eslint/no-var-requires
  const { Prisma } = require('@prisma/client');
  (Prisma.Decimal.prototype as any).toJSON = function () {
    return this.toString();
  };
} catch {
  /* ignore if Prisma isn't ready at build time */
}
// ---------------------------------------------------------------

async function bootstrap() {
  const app = await NestFactory.create(AppModule);

  // ✅ OPEN CORS (allows *, localhost, vercel, browser, postman)
  app.enableCors({
    origin: '*',
    methods: 'GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS',
    allowedHeaders: 'Authorization,Content-Type,Accept,Origin',
  });

  // All routes start with /api/v1
  app.setGlobalPrefix('api/v1');

  // ✅ Enable DTO validation + numeric transform
  app.useGlobalPipes(
    new ValidationPipe({
      transform: true,
      whitelist: true,
      forbidNonWhitelisted: false,
      transformOptions: { enableImplicitConversion: true },
    }),
  );

  // Serialize BigInt & Decimal in responses
  app.useGlobalInterceptors(new BigIntSerializerInterceptor());

  // Swagger
  const config = new DocumentBuilder()
    .setTitle('DVI Backend APIs')
    .setDescription('Hotels & Itineraries APIs with RBAC (admin/agent/vendor)')
    .setVersion('1.0.0')
    .addBearerAuth({
      type: 'http',
      scheme: 'bearer',
      bearerFormat: 'JWT',
      description:
        'Paste the JWT access token from /api/v1/auth/login here (without "Bearer ").',
    })
    .build();

  const doc = SwaggerModule.createDocument(app, config);
  SwaggerModule.setup('docs', app, doc);

  // Prisma graceful shutdown
  const prisma = app.get(PrismaService);
  await prisma.enableShutdownHooks(app);

  const port = Number(process.env.PORT) || 4006;
  await app.listen(port, '0.0.0.0');
  console.log(`Server running on http://localhost:${port}`);
  console.log(`Swagger docs at http://localhost:${port}/docs`);
}
bootstrap();
