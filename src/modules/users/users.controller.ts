import { Body, Controller, Get, Param, Patch } from '@nestjs/common';
import { ApiOperation, ApiParam, ApiTags } from '@nestjs/swagger';
import { UsersService } from './users.service';
import { Roles } from '../../auth/roles.decorator';
import { SwaggerRole } from '../../auth/swagger-role.enum';

@ApiTags('users')
@Controller('users')
export class UsersController {
  constructor(private users: UsersService) {}

  @ApiOperation({ summary: 'List users' })
  @Roles(SwaggerRole.admin as any)
  @Get()
  findAll() {
    return this.users.findAll();
  }

  @ApiOperation({ summary: 'Get user by id' })
  @ApiParam({ name: 'id' })
  @Roles(SwaggerRole.admin as any)
  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.users.findOne(id);
  }

  @ApiOperation({ summary: 'Set user role' })
  @ApiParam({ name: 'id' })
  @Patch(':id/role')
  setRole(@Param('id') id: string, @Body() body: { role: SwaggerRole }) {
    return this.users.setRole(id, body.role as any);
  }
}
