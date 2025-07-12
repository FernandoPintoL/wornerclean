import axios from 'axios';
import { route } from 'ziggy-js';

class EmpleadoService {
    path_url = 'empleado';

    async query(query = '', page = 1, perPage = 5) {
        try {
            const url = route(this.path_url + '.query', {
                query: query,
                is_query_table: true,
                page: page,
                perPage: perPage
            });
            return await axios.post(url);
        } catch (error) {
            console.error('Error en consulta:', error);
            throw error;
        }
    }

    async store(form) {
        try {
            const url = route(this.path_url + '.store');
            return await axios.post(url, form);
        } catch (error) {
            console.error('Error al guardar:', error);
            throw error;
        }
    }

    async update(form, id) {
        try {
            const url = route(this.path_url + '.update', id);
            return await axios.put(url, form);
        } catch (error) {
            console.error('Error al actualizar:', error);
            throw error;
        }
    }

    async destroy(id) {
        try {
            const url = route(this.path_url + '.destroy', id);
            return await axios.delete(url);
        } catch (error) {
            console.error('Error al eliminar:', error);
            throw error;
        }
    }
}

export default new EmpleadoService();
