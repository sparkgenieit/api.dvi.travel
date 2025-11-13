export class PaginationQueryDto {
  page?: number;
  limit?: number;
  search?: string;
}

export function normalizePagination(q: PaginationQueryDto) {
  const page = Math.max(1, Number(q.page) || 1);
  const take = Math.min(100, Math.max(1, Number(q.limit) || 20));
  const skip = (page - 1) * take;
  const search = (q.search || '').trim();
  return { page, take, skip, search };
}
