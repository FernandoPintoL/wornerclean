import { BaseData } from '@/Data/BaseData';
import axios from 'axios';
import { route } from 'ziggy-js';
import { Incidencias } from './Incidencias';

export interface ContratoIncidencias {
    id?: number;
    contrato_id: number;
    incidencia_id: number;
    estado?: string;
    descripcion?: string;
    fecha_solucion?: string;
    created_at?: string;
    updated_at?: string;
    // Include related data when needed
    incidencia?: Incidencias;
}

export class ContratoIncidenciasData extends BaseData<ContratoIncidencias> {
    protected path_api_url: string = 'api.contrato-incidencias';

    /**
     * Get all incidents for a specific contract
     */
    async getIncidenciasByContrato(contratoId: number) {
        try {
            const response = await axios.get(route('api.contrato-incidencias.incidencias-by-contrato', { contratoId }));
            return response.data;
        } catch (error) {
            console.error('Error fetching incidents by contract:', error);
            throw error;
        }
    }

    /**
     * Add an incident to a contract
     */
    async addIncidenciaToContrato(data: ContratoIncidencias) {
        try {
            const response = await axios.post(route('api.contrato-incidencias.store'), data);
            return response.data;
        } catch (error) {
            console.error('Error adding incident to contract:', error);
            throw error;
        }
    }

    /**
     * Update a contract incident
     */
    async updateContratoIncidencia(data: ContratoIncidencias) {
        try {
            const response = await axios.put(route('api.contrato-incidencias.update', { 'contrato-incidencias': data.id }), data);
            return response.data;
        } catch (error) {
            console.error('Error updating contract incident:', error);
            throw error;
        }
    }

    /**
     * Remove an incident from a contract
     */
    async removeIncidenciaFromContrato(id: number) {
        try {
            const response = await axios.delete(route('api.contrato-incidencias.destroy', { 'contrato-incidencias': id }));
            return response.data;
        } catch (error) {
            console.error('Error removing incident from contract:', error);
            throw error;
        }
    }
}

export function getDefaultAttributes() {
    return {
        id: true,
        contrato_id: true,
        incidencia_id: true,
        estado: true,
        descripcion: true,
        fecha_solucion: true,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}

export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'contrato_id', label: 'Contrato ID', isSearch: true },
    { key: 'incidencia_id', label: 'Incidencia ID', isSearch: true },
    { key: 'estado', label: 'Estado', isSearch: true },
    { key: 'descripcion', label: 'Descripción', isSearch: true },
    { key: 'fecha_solucion', label: 'Fecha Solución', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
