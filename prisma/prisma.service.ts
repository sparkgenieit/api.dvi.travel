// FILE: src/prisma/prisma.service.ts
import {
  INestApplication,
  Injectable,
  Logger,
  OnModuleInit,
} from '@nestjs/common';
import { Prisma, PrismaClient } from '@prisma/client';

@Injectable()
export class PrismaService extends PrismaClient implements OnModuleInit {
  private readonly logger = new Logger(PrismaService.name);
  private readonly queryLoggingEnabled: boolean;

  constructor() {
    // env flag to control whether we actually LOG queries
    const enabled =
      process.env.PRISMA_QUERY_LOG === '1' ||
      process.env.PRISMA_QUERY_LOG === 'true';

    // IMPORTANT: always declare log levels here so that
    // this.$on('query' | 'warn' | 'error') is correctly typed.
    super({
      log: [
        { emit: 'event', level: 'query' },
        { emit: 'event', level: 'warn' },
        { emit: 'event', level: 'error' },
      ],
    });

    this.queryLoggingEnabled = enabled;

  }

  async onModuleInit(): Promise<void> {
    await this.$connect();
    this.logger.log(
      `Connected to DB${
        this.queryLoggingEnabled ? ' (query logging ON)' : ''
      }`,
    );
  }

  async enableShutdownHooks(app: INestApplication): Promise<void> {
    (this as any).$on('beforeExit', async () => {
      await app.close();
    });
  }
}
