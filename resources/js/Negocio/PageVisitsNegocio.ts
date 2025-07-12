import { BaseNegocio } from '@/Negocio/BaseNegocio';
import { PageVisits, PageVisitsData } from '@/Data/PageVisits';

export class PageVisitsNegocio extends BaseNegocio<PageVisits>{
    public model: string = 'page-visits';
    protected dataService: PageVisitsData;

    constructor() {
        super();
        this.dataService = new PageVisitsData();
    }

    protected validar(pageVisits: PageVisits) {
        if (pageVisits.count < 0) {
            throw new Error('El conteo de visitas no puede ser negativo');
        }
    }
}
