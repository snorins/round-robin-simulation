import type { JsonApi } from '../types/api.type.ts';

export const isErrorResponse = (response: JsonApi<any>): boolean => {
    return Object.keys(response.errors).length > 0;
};
