// FILE: src/prisma/prisma.service.ts
import { INestApplication, Injectable, Logger, OnModuleInit } from "@nestjs/common";
import { Prisma, PrismaClient } from "@prisma/client";

@Injectable()
export class PrismaService extends PrismaClient implements OnModuleInit {
  private readonly logger = new Logger("Prisma");

  constructor() {
    // IMPORTANT:
    // Without this log config, `$on('query'...)` types often break (and also events may not fire).
    // Set PRISMA_QUERY_LOG=1 to enable.
    const enabled =
      process.env.PRISMA_QUERY_LOG === "1" || process.env.PRISMA_QUERY_LOG === "true";

    super({
      log: enabled
        ? [
            { emit: "event", level: "query" },
            { emit: "event", level: "warn" },
            { emit: "event", level: "error" },
          ]
        : [],
    });

    if (enabled) {
      this.$on("query", (e: Prisma.QueryEvent) => {
        // e.query = SQL, e.params = JSON-ish string (values), e.duration = ms
        this.logger.log(
          `[QUERY ${e.duration}ms] ${e.query} | params=${e.params}`
        );
      });

      this.$on("warn", (e: Prisma.LogEvent) => {
        this.logger.warn(e.message);
      });

      this.$on("error", (e: Prisma.LogEvent) => {
        this.logger.error(e.message);
      });
    }
  }

  async onModuleInit(): Promise<void> {
    await this.$connect();
    const enabled =
      process.env.PRISMA_QUERY_LOG === "1" || process.env.PRISMA_QUERY_LOG === "true";
    this.logger.log(`Connected${enabled ? " (query logging ON)" : ""}`);
  }

  async enableShutdownHooks(app: INestApplication): Promise<void> {
    (this as any).$on("beforeExit", async () => {
      await app.close();
    });
  }
}
