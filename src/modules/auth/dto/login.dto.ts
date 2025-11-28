// FILE: src/modules/auth/dto/login.dto.ts
import { ApiProperty } from '@nestjs/swagger';
import { IsEmail, IsString, MinLength } from 'class-validator';

export class LoginDto {
  @ApiProperty({ example: 'admin@dvi.co.in' })
  @IsEmail()
  email!: string;

  @ApiProperty({ example: 'Keerthi@2404ias' })
  @IsString()
  @MinLength(1)
  password!: string;
}
