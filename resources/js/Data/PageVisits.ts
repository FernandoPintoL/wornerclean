import { BaseData } from '@/Data/BaseData';
export interface PageVisits {
    id?: string;
    count: number;
    created_at?: string;
    updated_at?: string;
}
export class PageVisitsData extends BaseData<PageVisits> {
    protected path_api_url: string = 'api.page-visits';
}
