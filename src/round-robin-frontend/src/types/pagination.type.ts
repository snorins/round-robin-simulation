export type PaginatedEntries<Entry> = {
    entries: Entry[];
    total: number;
    page: number;
    limit: number;
    pages: number;
    from: number;
    to: number;
}
