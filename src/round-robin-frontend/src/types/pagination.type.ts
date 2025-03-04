export type PaginatedEntries<Data> = {
    entries: Data[];
    total: number;
    page: number;
    limit: number;
    pages: number;
    from: number;
    to: number;
}
