import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { PaginationQueryDto, normalizePagination } from '../../common/pagination';
import { Prisma } from '@prisma/client';
import { CreateItineraryDto } from './dto/create-itinerary.dto';
import { UpdateItineraryDto } from './dto/update-itinerary.dto';
import { CreateDayDto } from './dto/create-day.dto';
import { CreateSegmentDto } from './dto/create-segment.dto';

@Injectable()
export class ItinerariesService {
  constructor(private prisma: PrismaService) {}

  async list(q: PaginationQueryDto) {
    const { page, take, skip, search } = normalizePagination(q);
    const where: Prisma.ItineraryWhereInput = search
      ? { OR: [{ title: { contains: search, mode: 'insensitive' } }, { code: { contains: search, mode: 'insensitive' } }] }
      : {};
    const [items, total] = await this.prisma.$transaction([
      this.prisma.itinerary.findMany({ where, skip, take, orderBy: { createdAt: 'desc' } }),
      this.prisma.itinerary.count({ where }),
    ]);
    return { page, total, items };
  }

  create(dto: CreateItineraryDto) {
    return this.prisma.itinerary.create({ data: dto });
  }

  async get(id: string) {
    const it = await this.prisma.itinerary.findUnique({
      where: { id },
      include: { days: { include: { segments: true }, orderBy: { dayNumber: 'asc' } } },
    });
    if (!it) throw new NotFoundException('Itinerary not found');
    return it;
  }

  async update(id: string, dto: UpdateItineraryDto) {
    await this.ensure(id);
    return this.prisma.itinerary.update({ where: { id }, data: dto });
  }

  async remove(id: string) {
    await this.ensure(id);
    await this.prisma.itinerary.delete({ where: { id } });
    return { success: true };
  }

  // Days
  async addDay(itineraryId: string, dto: CreateDayDto) {
    await this.ensure(itineraryId);
    return this.prisma.itineraryDay.create({ data: { itineraryId, ...dto } });
  }
  async deleteDay(itineraryId: string, dayId: string) {
    await this.ensure(itineraryId);
    return this.prisma.itineraryDay.delete({ where: { id: dayId } });
  }

  // Segments
  async addSegment(dayId: string, dto: CreateSegmentDto) {
    const day = await this.prisma.itineraryDay.findUnique({ where: { id: dayId } });
    if (!day) throw new NotFoundException('Day not found');
    return this.prisma.itinerarySegment.create({ data: { dayId, ...dto } });
  }
  async deleteSegment(dayId: string, segmentId: string) {
    const seg = await this.prisma.itinerarySegment.findUnique({ where: { id: segmentId } });
    if (!seg || seg.dayId !== dayId) throw new NotFoundException('Segment not found');
    return this.prisma.itinerarySegment.delete({ where: { id: segmentId } });
  }

  private async ensure(id: string) {
    const it = await this.prisma.itinerary.findUnique({ where: { id } });
    if (!it) throw new NotFoundException('Itinerary not found');
  }
}
