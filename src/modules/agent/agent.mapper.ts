export type AgentListRow = {
  sno: string;
  agentname: string;
  agentemail: string;
  mobilenumber: string;
  travelexpert: string | null;
  city: string | null;
  state: string | null;
  nationality: string | null;
  subscription_title: string;
  status: '0' | '1';
  modify: string;
};

export function mapLoginStatus(user: { userapproved: number; userbanned: number } | null): '0' | '1' {
  if (!user) return '0';
  const ok = user.userapproved === 1 && user.userbanned === 0;
  return ok ? '1' : '0';
}

export function mapAgentToListRow(
  a: any,
  idx: number,
  labelers?: {
    country?: (id?: number | null) => string | null;
    state?: (id?: number | null, countryId?: number | null) => string | null;
    city?: (id?: number | null, stateId?: number | null) => string | null;
    travelExpert?: (id?: number | null) => string | null;
    subscription?: (id?: number | null) => { title: string; days?: number | null } | null;
  },
): AgentListRow {
  const countryLabel = labelers?.country?.(a.agent_country ?? null) ?? null;
  const stateLabel = labelers?.state?.(a.agent_state ?? null, a.agent_country ?? null) ?? null;
  const cityLabel = labelers?.city?.(a.agent_city ?? null, a.agent_state ?? null) ?? null;
  const trExp = labelers?.travelExpert?.(a.travel_expert_id ?? null) ?? null;
  const sub = labelers?.subscription?.(a.subscription_plan_id ?? null);
  const subTitle = sub ? `${sub.title}${sub.days ? ` / ${sub.days} Days` : ''}` : '—';

  return {
    sno: String(idx + 1),
    agentname: [a.agent_name, a.agent_lastname].filter(Boolean).join(' ').trim() || '—',
    agentemail: a.agent_email_id ?? '—',
    mobilenumber: a.agent_primary_mobile_number ?? '—',
    travelexpert: trExp,
    city: cityLabel,
    state: stateLabel,
    nationality: countryLabel,
    subscription_title: subTitle,
    status: (a.user && a.user.userapproved === 1 && a.user.userbanned === 0) ? '1' : '0',
    modify: String(a.agent_ID),
  };
}
