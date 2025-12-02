// REPLACE-WHOLE-FILE
// FILE: src/modules/hotspots/hotspots.controller.ts

import {
  BadRequestException,
  Body,
  Controller,
  Delete,
  Get,
  Param,
  Patch,
  Post,
  Query,
  UploadedFile,
  UseInterceptors,
} from '@nestjs/common';
import { HotspotsService } from './hotspots.service';
import { HotspotListQueryDto } from './dto/hotspot-list.query.dto';
import { HotspotListResponseDto } from './dto/hotspot-list.response.dto';

// ✅ NEW: use just Create/Update DTOs
import { HotspotCreateDto } from './dto/hotspot-create.dto';
import { HotspotUpdateDto } from './dto/hotspot-update.dto';

import { FileInterceptor } from '@nestjs/platform-express';
import { diskStorage } from 'multer';
import * as path from 'path';
import * as crypto from 'crypto';

// storage for gallery images (unchanged behavior besides adding route)
function galleryStorage() {
  return diskStorage({
    destination: (_req, _file, cb) =>
      cb(null, path.join(process.cwd(), 'public', 'uploads', 'hotspot_gallery')),
    filename: (_req, file, cb) => {
      const ext = path.extname(file.originalname || '').toLowerCase();
      const base = crypto.randomBytes(16).toString('hex');
      cb(null, `${base}${ext}`);
    },
  });
}

@Controller('hotspots')
export class HotspotsController {
  constructor(private readonly svc: HotspotsService) {}

  // List JSON (DataTable)
  @Get()
  list(@Query() q: HotspotListQueryDto): Promise<HotspotListResponseDto> {
    return this.svc.list(q);
  }

  // Dynamic dropdowns for form
  @Get('form-options')
  formOptions() {
    return this.svc.formOptions();
  }

  // Full form payload for edit (master + children) + options
  @Get(':id/form')
  getForm(@Param('id') id: string) {
    return this.svc.getForm(Number(id));
  }

  // Save form (create or update) — accepts either DTO
  @Post('form')
  saveForm(@Body() payload: HotspotCreateDto | HotspotUpdateDto) {
    return this.svc.saveForm(payload as any);
  }

  // Inline priority update
  @Patch(':id/priority')
  updatePriority(@Param('id') id: string, @Body() body: { priority: number }) {
    const priority = Number(body?.priority);
    return this.svc.updatePriority(Number(id), priority);
  }

  // Soft delete
  @Delete(':id')
  softDelete(@Param('id') id: string) {
    return this.svc.softDelete(Number(id));
  }

  // Simple fetch
  @Get(':id')
  getOne(@Param('id') id: string) {
    return this.svc.getOne(Number(id));
  }

  // NEW: Gallery upload endpoint (multipart/form-data; field name: file)
  @Post(':id/gallery/upload')
  @UseInterceptors(
    FileInterceptor('file', {
      storage: galleryStorage(),
      limits: { fileSize: 8 * 1024 * 1024 }, // 8MB
    }),
  )
  async uploadGallery(@Param('id') id: string, @UploadedFile() file: Express.Multer.File) {
    if (!file) throw new BadRequestException('No file uploaded');
    const row = await this.svc.createGalleryRow(Number(id), file.filename);
    return {
      ok: true,
      id: row.hotspot_gallery_details_id,
      name: file.filename,
      url: `/uploads/hotspot_gallery/${file.filename}`,
    };
  }
}
