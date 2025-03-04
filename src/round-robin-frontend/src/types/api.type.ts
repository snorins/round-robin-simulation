export type JsonApi<Data> = {
    data: Data,
    errors: JsonApiErrors;
}

export type JsonApiErrors = Record<string, ErrorStructure>;

export type ErrorStructure = {
    message: string;
    oldValue?: string | number;
}
