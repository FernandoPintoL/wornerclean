import { BaseData } from '@/Data/BaseData';
import axios from 'axios';
import { route } from 'ziggy-js';

export interface EmpleadoEquipoTrabajo {
    id?: number;
    empleado_id: number;
    equipo_trabajo_id: number;
    estado?: string;
    ocupacion?: string;
    created_at?: string;
    updated_at?: string;
    // Include related data when needed
    empleado?: any;
    equipoTrabajo?: any;
}

export class EmpleadoEquipoTrabajoData extends BaseData<EmpleadoEquipoTrabajo> {
    protected path_api_url: string = 'api.empleado-equipo-trabajo';

    /**
     * Get all employees for a specific work team
     */
    async getEmpleadosByEquipoTrabajo(equipoTrabajoId: number) {
        try {
            const response = await axios.get(route('api.empleado-equipo-trabajo.empleados-by-equipo', { equipoTrabajoId }));
            return response.data;
        } catch (error) {
            console.error('Error fetching employees by work team:', error);
            throw error;
        }
    }

    /**
     * Get all work teams for a specific employee
     */
    async getEquipoTrabajosByEmpleado(empleadoId: number) {
        try {
            const response = await axios.get(route('api.empleado-equipo-trabajo.equipos-by-empleado', { empleadoId }));
            return response.data;
        } catch (error) {
            console.error('Error fetching work teams by employee:', error);
            throw error;
        }
    }

    /**
     * Assign an employee to a work team
     */
    async assignEmpleadoToEquipoTrabajo(data: EmpleadoEquipoTrabajo) {
        try {
            const response = await axios.post(route('api.empleado-equipo-trabajo.assign'), data);
            return response.data;
        } catch (error) {
            console.error('Error assigning employee to work team:', error);
            throw error;
        }
    }

    /**
     * Remove an employee from a work team
     */
    async removeEmpleadoFromEquipoTrabajo(id: number) {
        try {
            const response = await axios.delete(route('api.empleado-equipo-trabajo.remove', { id }));
            return response.data;
        } catch (error) {
            console.error('Error removing employee from work team:', error);
            throw error;
        }
    }
}

export function getDefaultAttributes() {
    return {
        id: true,
        empleado_id: true,
        equipo_trabajo_id: true,
        estado: true,
        ocupacion: true,
        created_at: false,
        updated_at: false,
    } as Record<string, boolean>;
}

export const selectedAttributes = getDefaultAttributes();
export const attributesHead = [
    { key: 'id', label: 'ID', isSearch: true },
    { key: 'empleado_id', label: 'Empleado ID', isSearch: true },
    { key: 'equipo_trabajo_id', label: 'Equipo Trabajo ID', isSearch: true },
    { key: 'estado', label: 'Estado', isSearch: true },
    { key: 'ocupacion', label: 'Ocupación', isSearch: true },
    { key: 'acciones', label: 'Acciones', isSearch: false },
];
