// FILE: src/modules/auth/auth.controller.ts
import { Body, Controller, Post } from '@nestjs/common';
import { ApiBody, ApiOperation, ApiProperty, ApiTags } from '@nestjs/swagger';
import { AuthService } from './auth.service';
import { Public } from '../../auth/public.decorator';

class LoginDto {
  @ApiProperty({
    example: 'admin@dvi.co.in',
    description: 'Registered user email',
  })
  email!: string;

  @ApiProperty({
    example: 'Keerthi@2404ias',
    description: 'User password',
  })
  password!: string;
}

@ApiTags('auth')
@Controller('auth')
export class AuthController {
  constructor(private auth: AuthService) {}

  @ApiOperation({ summary: 'Login and receive JWT' })
  @ApiBody({ type: LoginDto })
  @Public()
  @Post('login')
  login(@Body() body: LoginDto) {
    return this.auth.login(body.email, body.password);
  }
}
