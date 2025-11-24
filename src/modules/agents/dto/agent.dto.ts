// FILE: src/modules/agents/dto/agent.dto.ts

import { ApiProperty } from "@nestjs/swagger";

export class AgentListItemDto {
  @ApiProperty()
  id: number;

  @ApiProperty()
  name: string;
}

export class AgentWithCompanyDto {
  @ApiProperty()
  id: number;

  @ApiProperty({ description: "Agent name" })
  name: string;

  @ApiProperty({ description: "Company name (or 'NA')" })
  companyName: string;

  @ApiProperty({ description: "Label 'Agent Name - Company'" })
  label: string;
}
