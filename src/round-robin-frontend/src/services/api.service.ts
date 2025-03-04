import type { JsonApi } from '../types/api.type.ts';
import { API_BASE_URL } from '../constants/api.constant.ts';

export class ApiService {
    public static async get<Data>(path: string): Promise<JsonApi<Data>> {
        const response = await fetch(API_BASE_URL + path);

        return response.json();
    }

    public static async post<Data>(path: string, body: Record<string, string>): Promise<JsonApi<Data>> {
        const response = await fetch(API_BASE_URL + path, {
            body: JSON.stringify(body),
            method: 'POST',
        });

        return response.json();
    }
}
