<script setup lang="ts">
import HeaderForm from '@/Componentes/Header-Form.vue';
import AlertService from '@/Services/AlertService.js';
import UtilsServices from '@/Services/UtilsServices';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, reactive } from 'vue';
import { route } from 'ziggy-js';
import { Pencil, DiamondPlus } from 'lucide-vue-next';
import { ServicioNegocio } from '@/Negocio/ServicioNegocio';
import { Servicio } from '@/Data/Servicio';
import { ProductoNegocio } from '@/Negocio/ProductoNegocio';
import { ProductoServicioNegocio } from '@/Negocio/ProductoServicioNegocio';
import { ProductoServicio } from '@/Data/ProductoServicio';
import { EquipoTrabajoNegocio } from '@/Negocio/EquipoTrabajoNegocio';
import { EquipoTrabajoServicioNegocio } from '@/Negocio/EquipoTrabajoServicioNegocio';
import { EquipoTrabajoServicio } from '@/Data/EquipoTrabajoServicio';
import { type EquipoTrabajo } from '@/Data/EquipoTrabajo';
import SearchableSelect from '@/Componentes/SearchableSelect.vue';
import { type Producto } from '@/Data/Producto';

const negocio = new ServicioNegocio();
const productoNegocio = new ProductoNegocio();
const productoServicioNegocio = new ProductoServicioNegocio();
const equipoTrabajoNegocio = new EquipoTrabajoNegocio();
const equipoTrabajoServicioNegocio = new EquipoTrabajoServicioNegocio();
const model_path = negocio.model;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: model_path.toUpperCase(),
        href: '/' + model_path,
    },
];

const props = defineProps({
    model: Object as () => Servicio | null,
    isCreate: {
        type: Boolean,
        default: false,
    },
    crear: {
        type: Boolean,
        default: false,
    },
    editar: {
        type: Boolean,
        default: false,
    },
    eliminar: {
        type: Boolean,
        default: false,
    },
});

let use_model: Servicio;
const form = useForm({
    id: props.model != null ? props.model.id : '',
    nombre: props.model != null ? props.model.nombre : '',
    descripcion: props.model != null ? props.model.descripcion : '',
    precio: props.model != null ? props.model.precio : '',
    frecuencia: props.model != null ? props.model.frecuencia : '',
    estado: props.model != null ? props.model.estado : '',
});

const cargarProductos = async () => {
    try {
        const response = await productoNegocio.consultar({
            query: '',
        });
        if (response.isSuccess && response.data) {
            datas.productos = response.data || [];
        } else {
            AlertService.error('Error al cargar los productos');
        }
    } catch (error) {
        console.error('Error al cargar productos:', error);
        AlertService.error('Error al cargar los productos');
    }
};

const cargarEquiposTrabajo = async () => {
    try {
        const response = await equipoTrabajoNegocio.consultar({
            query: '',
        });
        if (response.isSuccess && response.data) {
            datas.equiposTrabajo = response.data || [];
        } else {
            AlertService.error('Error al cargar los equipos de trabajo');
        }
    } catch (error) {
        console.error('Error al cargar equipos de trabajo:', error);
        AlertService.error('Error al cargar los equipos de trabajo');
    }
};

const cargarProductosServicio = async (servicioId: number) => {
    try {
        const response = await productoServicioNegocio.consultar({
            query: servicioId.toString(),
        });
        console.log(response);
        if (response.isSuccess && response.data) {
            datas.productosSeleccionados = response.data || [];
        } else {
            AlertService.error('Error al cargar los productos del servicio');
        }
    } catch (error) {
        console.error('Error al cargar productos del servicio:', error);
        AlertService.error('Error al cargar los productos del servicio');
    }
};

const cargarEquiposTrabajoServicio = async (servicioId: number) => {
    try {
        const response = await equipoTrabajoServicioNegocio.consultar({
            query: servicioId.toString(),
        });
        console.log(response);
        if (response.isSuccess && response.data) {
            datas.equiposTrabajoSeleccionados = response.data || [];
        } else {
            AlertService.error('Error al cargar los equipos de trabajo del servicio');
        }
    } catch (error) {
        console.error('Error al cargar equipos de trabajo del servicio:', error);
        AlertService.error('Error al cargar los equipos de trabajo del servicio');
    }
};

const agregarProducto = () => {
    if (!datas.productoSeleccionado) {
        datas.cantidadError = 'Debe seleccionar un producto';
        return;
    }

    if (datas.cantidadProducto <= 0) {
        datas.cantidadError = 'La cantidad debe ser mayor a cero';
        return;
    }

    // Verificar si el producto ya está en la lista
    const productoExistente = datas.productosSeleccionados.find((p) => p.producto_id === datas.productoSeleccionado);

    if (productoExistente) {
        datas.cantidadError = 'Este producto ya está asignado al servicio';
        return;
    }

    // Agregar el producto a la lista
    datas.productosSeleccionados.push({
        servicio_id: Number(form.id) || 0,
        producto_id: datas.productoSeleccionado,
        cantidad: datas.cantidadProducto,
    });

    // Limpiar selección
    datas.productoSeleccionado = null;
    datas.cantidadProducto = 1;
    datas.cantidadError = '';
};

const agregarEquipoTrabajo = () => {
    if (!datas.equipoTrabajoSeleccionado) {
        datas.equipoTrabajoError = 'Debe seleccionar un equipo de trabajo';
        return;
    }

    // Verificar si el equipo de trabajo ya está en la lista
    const equipoExistente = datas.equiposTrabajoSeleccionados.find(
        (e) => e.equipo_trabajo_id === datas.equipoTrabajoSeleccionado
    );

    if (equipoExistente) {
        datas.equipoTrabajoError = 'Este equipo de trabajo ya está asignado al servicio';
        return;
    }

    // Agregar el equipo de trabajo a la lista
    datas.equiposTrabajoSeleccionados.push({
        servicio_id: Number(form.id) || 0,
        equipo_trabajo_id: datas.equipoTrabajoSeleccionado,
        estado: 'activo',
    });

    // Limpiar selección
    datas.equipoTrabajoSeleccionado = null;
    datas.equipoTrabajoError = '';
};

const eliminarProducto = (index: number) => {
    datas.productosSeleccionados.splice(index, 1);
};

const eliminarEquipoTrabajo = (index: number) => {
    datas.equiposTrabajoSeleccionados.splice(index, 1);
};

const getNombreProducto = (productoId: number) => {
    const producto = datas.productos.find((p : Producto) => p.id === productoId);
    return producto ? producto.nombre : 'Producto no encontrado';
};

const getNombreEquipoTrabajo = (equipoTrabajoId: number) => {
    const equipoTrabajo = datas.equiposTrabajo.find((e : EquipoTrabajo) => e.id === equipoTrabajoId);
    return equipoTrabajo ? equipoTrabajo.nombre : 'Equipo de trabajo no encontrado';
};

onMounted(async () => {
    await cargarProductos();
    await cargarEquiposTrabajo();
    console.log(props.model);
    if (props.model) {
        form.id = props.model.id;
        form.nombre = props.model.nombre;
        form.descripcion = props.model.descripcion;
        form.precio = props.model.precio;
        form.frecuencia = props.model.frecuencia;
        form.estado = props.model.estado;

        // Cargar productos asociados al servicio
        if (props.model.id) {
            await cargarProductosServicio(props.model.id);
            await cargarEquiposTrabajoServicio(props.model.id);
        }
    }
});

const datas = reactive({
    isLoad: false,
    nombreError: '',
    descripcionError: '',
    precioError: '',
    frecuenciaError: '',
    estadoError: '',
    generalError: '',
    productos: [] as Producto[],
    productosSeleccionados: [] as ProductoServicio[],
    productoSeleccionado: null as number | null,
    cantidadProducto: 1,
    cantidadError: '',
    equiposTrabajo: [] as EquipoTrabajo[],
    equiposTrabajoSeleccionados: [] as EquipoTrabajoServicio[],
    equipoTrabajoSeleccionado: null as number | null,
    equipoTrabajoError: '',
});

function cargarUseModel() {
    use_model = {
        id: Number(form.id) ?? 0,
        nombre: form.nombre,
        descripcion: form.descripcion,
        precio: Number(form.precio),
        frecuencia: form.frecuencia,
        estado: form.estado,
    };
}

const create_model = async () => {
    try {
        cargarUseModel();
        console.log(use_model);
        const response = await negocio.guardar(use_model);
        console.log(response);
        if (response.isSuccess && response.data) {
            // Guardar los productos asociados al servicio
            const servicioId = response.data.id;

            // Actualizar el ID del servicio en los productos seleccionados
            for (const productoServicio of datas.productosSeleccionados) {
                productoServicio.servicio_id = servicioId;

                try {
                    await productoServicioNegocio.guardar(productoServicio);
                } catch (error) {
                    console.error('Error al guardar producto-servicio:', error);
                    // Continuar con los demás productos aunque uno falle
                }
            }

            // Actualizar el ID del servicio en los equipos de trabajo seleccionados
            for (const equipoTrabajoServicio of datas.equiposTrabajoSeleccionados) {
                equipoTrabajoServicio.servicio_id = servicioId;

                try {
                    await equipoTrabajoServicioNegocio.guardar(equipoTrabajoServicio);
                } catch (error) {
                    console.error('Error al guardar equipo-trabajo-servicio:', error);
                    // Continuar con los demás equipos aunque uno falle
                }
            }

            await AlertService.success('La operación se completó con éxito.').then(() => {
                window.location.href = route(model_path + '.index');
            });
            form.reset();
        } else {
            await AlertService.error(response.message);
        }
    } catch (error) {
        handleErrors(error);
    }
};

const editar_model = async () => {
    try {
        cargarUseModel();
        const response = await negocio.actualizar(use_model, form.id ?? 0);
        if (response.isSuccess) {
            // Actualizar los productos asociados al servicio
            const servicioId = Number(form.id);

            // Primero, eliminar todas las relaciones existentes de productos
            try {
                // Obtener las relaciones actuales
                const relacionesActuales = await productoServicioNegocio.consultar({
                    query: servicioId.toString(),
                    // page: 1,
                    // perPage: 100,
                    // filters: {
                    //     servicio_id: servicioId
                    // }
                });

                // Eliminar cada relación
                if (relacionesActuales.isSuccess && relacionesActuales.data) {
                    for (const relacion of relacionesActuales.data.data || []) {
                        if (relacion.id) {
                            await productoServicioNegocio.eliminar(relacion.id);
                        }
                    }
                }
            } catch (error) {
                console.error('Error al eliminar relaciones de productos existentes:', error);
            }

            // Luego, crear las nuevas relaciones de productos
            for (const productoServicio of datas.productosSeleccionados) {
                productoServicio.servicio_id = servicioId;

                try {
                    await productoServicioNegocio.guardar(productoServicio);
                } catch (error) {
                    console.error('Error al guardar producto-servicio:', error);
                    // Continuar con los demás productos aunque uno falle
                }
            }

            // Actualizar los equipos de trabajo asociados al servicio
            // Primero, eliminar todas las relaciones existentes de equipos de trabajo
            try {
                // Obtener las relaciones actuales
                const relacionesActuales = await equipoTrabajoServicioNegocio.consultar({
                    query: servicioId.toString(),
                });

                // Eliminar cada relación
                if (relacionesActuales.isSuccess && relacionesActuales.data) {
                    for (const relacion of relacionesActuales.data.data || []) {
                        if (relacion.id) {
                            await equipoTrabajoServicioNegocio.eliminar(relacion.id);
                        }
                    }
                }
            } catch (error) {
                console.error('Error al eliminar relaciones de equipos de trabajo existentes:', error);
            }

            // Luego, crear las nuevas relaciones de equipos de trabajo
            for (const equipoTrabajoServicio of datas.equiposTrabajoSeleccionados) {
                equipoTrabajoServicio.servicio_id = servicioId;

                try {
                    await equipoTrabajoServicioNegocio.guardar(equipoTrabajoServicio);
                } catch (error) {
                    console.error('Error al guardar equipo-trabajo-servicio:', error);
                    // Continuar con los demás equipos aunque uno falle
                }
            }

            await AlertService.success('La operación se completó con éxito.').then(() => {
                window.location.href = route(model_path + '.index');
            });
        } else {
            await AlertService.error(response.message);
        }
    } catch (error) {
        handleErrors(error);
    }
};

const submit_create = async () => {
    if (props.isCreate) {
        await create_model();
    } else {
        await editar_model();
    }
};

const handleErrors = (error: any) => {
    if (error.response.status === 403) {
        AlertService.error('No tiene permiso para realizar esta acción.');
        return;
    }
    if (error.response.data && error.response.data.statusCode === 422) {
        const errors = error.response.data.messageError;
        for (const key in errors) {
            if (key === 'nombre') {
                datas.nombreError = errors[key][0];
            } else if (key === 'descripcion') {
                datas.descripcionError = errors[key][0];
            } else if (key === 'precio') {
                datas.precioError = errors[key][0];
            } else if (key === 'frecuencia') {
                datas.frecuenciaError = errors[key][0];
            } else if (key === 'estado') {
                datas.estadoError = errors[key][0];
            }
        }
    } else {
        AlertService.error(error.response.data.message);
        datas.generalError = 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.';
    }
};
</script>

<template>
    <Head :title="UtilsServices.capitalizeFirstLetter(model_path)" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 transition-all duration-300 sm:p-6 lg:p-8">
            <div class="mx-auto w-full max-w-7xl">
                <!-- Header card with shadow and rounded corners -->
                <div class="animate-fadeIn mb-6 rounded-lg bg-white p-6 shadow-md transition-all duration-300 dark:bg-gray-800">
                    <HeaderForm
                        :model_path="model_path"
                        :fecha_creado="props.model ? props.model.created_at : ''"
                        :fecha_actualizado="props.model ? props.model.updated_at : ''"
                        :isCreate="props.isCreate"
                        :id_model="props.model ? String(props.model.id) : ''"
                        class="mb-4"
                    />

                    <!-- Form divider with gradient -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white px-4 text-sm text-gray-500 dark:bg-gray-800 dark:text-gray-400"> Información del Servicio </span>
                        </div>
                    </div>

                    <!-- Loading overlay -->
                    <div v-if="datas.isLoad" class="animate-fadeIn fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
                        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl dark:bg-gray-800">
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-16 animate-spin rounded-full border-4 border-indigo-500 border-t-transparent"></div>
                                <p class="mt-4 font-medium text-gray-700 dark:text-gray-300">Procesando solicitud...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form with responsive grid layout -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Nombre -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.1s">
                            <label
                                for="nombre"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.nombreError ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300',
                                ]"
                                >Nombre *</label
                            >
                            <div class="relative">
                                <input
                                    type="text"
                                    name="nombre"
                                    id="nombre"
                                    v-model="form.nombre"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.nombreError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500',
                                    ]"
                                    placeholder="Nombre del producto"
                                    required
                                />
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3" v-if="datas.nombreError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.nombreError" />
                        </div>

                        <!-- Descripción -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.2s">
                            <label
                                for="descripcion"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.descripcionError ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300',
                                ]"
                                >Descripción *</label
                            >
                            <div class="relative">
                                <textarea
                                    name="descripcion"
                                    id="descripcion"
                                    v-model="form.descripcion"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.descripcionError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500',
                                    ]"
                                    placeholder="Descripción del producto"
                                    rows="3"
                                    required
                                ></textarea>
                                <div class="pointer-events-none absolute top-3 right-3 flex items-center pr-3" v-if="datas.descripcionError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.descripcionError" />
                        </div>

                        <!-- Precio -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.3s">
                            <label
                                for="precio"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.precioError ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300',
                                ]"
                                >Precio *</label
                            >
                            <div class="relative">
                                <input
                                    type="number"
                                    name="precio"
                                    id="precio"
                                    v-model="form.precio"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.precioError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500',
                                    ]"
                                    placeholder="Precio del producto"
                                    step="0.01"
                                    min="0"
                                    required
                                />
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3" v-if="datas.precioError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.precioError" />
                        </div>

                        <!-- Frecuencia -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.4s">
                            <label
                                for="frecuencia"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.frecuenciaError ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300',
                                ]"
                                >Frecuencia *</label
                            >
                            <div class="relative">
                                <select
                                    name="frecuencia"
                                    id="frecuencia"
                                    v-model="form.frecuencia"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.frecuenciaError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500',
                                    ]"
                                    required
                                >
                                    <option value="" disabled>Seleccione una frecuencia</option>
                                    <option
                                        v-for="option in [
                                            { value: 'diaria', label: 'Diaria' },
                                            { value: 'semanal', label: 'Semanal' },
                                            { value: 'quincenal', label: 'Quincenal' },
                                            { value: 'mensual', label: 'Mensual' },
                                            { value: 'bimestral', label: 'Bimestral' },
                                            { value: 'trimestral', label: 'Trimestral' },
                                            { value: 'semestral', label: 'Semestral' },
                                            { value: 'anual', label: 'Anual' },
                                            { value: 'unica', label: 'Única vez' },
                                        ]"
                                        :key="option.value"
                                        :value="option.value"
                                        :selected="form.frecuencia.toUpperCase() === option.value.toUpperCase()"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3" v-if="datas.frecuenciaError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <p>{{ form.frecuencia }}</p>
                            <InputError class="mt-2" :message="datas.frecuenciaError" />
                        </div>

                        <!-- Estado -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.5s">
                            <label
                                for="estado"
                                :class="[
                                    'mb-2 block text-sm font-medium transition-colors duration-200',
                                    datas.estadoError ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300',
                                ]"
                                >Estado *</label
                            >
                            <div class="relative">
                                <select
                                    name="estado"
                                    id="estado"
                                    v-model="form.estado"
                                    :class="[
                                        'block w-full rounded-lg border p-3 text-sm shadow-sm transition-all duration-200',
                                        datas.estadoError
                                            ? 'border-red-500 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 dark:border-red-400 dark:bg-red-900/10 dark:text-red-400'
                                            : 'border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500',
                                    ]"
                                    required
                                >
                                    <option value="">Seleccione un estado</option>
                                    <option value="activo" :selected="form.estado.toUpperCase() === 'activo'.toUpperCase()">Activo</option>
                                    <option value="inactivo" :selected="form.estado.toUpperCase() === 'inactivo'.toUpperCase()">Inactivo</option>
                                    <option value="suspendido" :selected="form.estado.toUpperCase() === 'suspendido'.toUpperCase()">
                                        Suspendido
                                    </option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3" v-if="datas.estadoError">
                                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="datas.estadoError" />
                        </div>
                    </div>

                    <!-- Form divider with gradient for Products section -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white px-4 text-sm text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                Productos utilizados en el servicio
                            </span>
                        </div>
                    </div>

                    <!-- Products section -->
                    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Product selection -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.6s; z-index: 1000 !important">
                            <label for="producto" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Producto</label>
                            <SearchableSelect
                                :options="datas.productos"
                                v-model="datas.productoSeleccionado"
                                label="Seleccionar producto"
                                placeholder="Buscar producto..."
                                valueKey="id"
                                labelKey="nombre"
                                :error="datas.cantidadError"
                            />
                        </div>

                        <!-- Quantity -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.6s; z-index: 1 !important">
                            <label for="cantidad" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Cantidad</label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="cantidad"
                                    id="cantidad"
                                    v-model="datas.cantidadProducto"
                                    class="block w-full rounded-lg border border-gray-300 bg-white p-3 text-sm text-gray-900 shadow-sm transition-all duration-200 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-indigo-500"
                                    placeholder="Cantidad"
                                    min="1"
                                    step="1"
                                />
                            </div>
                        </div>

                        <!-- Add button -->
                        <div class="form-group animate-fadeIn flex items-end" style="animation-delay: 0.6s">
                            <button
                                type="button"
                                @click="agregarProducto"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 text-base font-medium text-white shadow-md transition-all duration-300 hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none dark:from-indigo-700 dark:to-purple-700 dark:hover:from-indigo-800 dark:hover:to-purple-800"
                            >
                                <span>Agregar producto</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Selected products table -->
                    <div class="animate-fadeIn overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700" style="animation-delay: 0.7s">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                    >
                                        Producto
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                    >
                                        Cantidad
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-600 dark:bg-gray-700">
                                <tr v-if="datas.productosSeleccionados.length === 0">
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No hay productos seleccionados
                                    </td>
                                </tr>
                                <tr
                                    v-for="(producto, index) in datas.productosSeleccionados"
                                    :key="index"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-600"
                                >
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ getNombreProducto(producto.producto_id) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-300">
                                        {{ producto.cantidad }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap">
                                        <button
                                            @click="eliminarProducto(index)"
                                            class="text-red-600 transition-colors duration-200 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Form divider with gradient for Work Teams section -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white px-4 text-sm text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                Equipos de trabajo asignados al servicio
                            </span>
                        </div>
                    </div>

                    <!-- Work Teams section -->
                    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Work Team selection -->
                        <div class="form-group animate-fadeIn" style="animation-delay: 0.6s; z-index: 1000 !important">
                            <label for="equipoTrabajo" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Equipo de Trabajo</label>
                            <SearchableSelect
                                :options="datas.equiposTrabajo"
                                v-model="datas.equipoTrabajoSeleccionado"
                                label="Seleccionar equipo de trabajo"
                                placeholder="Buscar equipo de trabajo..."
                                valueKey="id"
                                labelKey="nombre"
                                :error="datas.equipoTrabajoError"
                            />
                        </div>

                        <!-- Add button -->
                        <div class="form-group animate-fadeIn flex items-end" style="animation-delay: 0.6s">
                            <button
                                type="button"
                                @click="agregarEquipoTrabajo"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 text-base font-medium text-white shadow-md transition-all duration-300 hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none dark:from-indigo-700 dark:to-purple-700 dark:hover:from-indigo-800 dark:hover:to-purple-800"
                            >
                                <span>Agregar equipo de trabajo</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Selected work teams table -->
                    <div class="animate-fadeIn overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700" style="animation-delay: 0.7s">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                    >
                                        Equipo de Trabajo
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                    >
                                        Estado
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-600 dark:bg-gray-700">
                                <tr v-if="datas.equiposTrabajoSeleccionados.length === 0">
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No hay equipos de trabajo asignados
                                    </td>
                                </tr>
                                <tr
                                    v-for="(equipo, index) in datas.equiposTrabajoSeleccionados"
                                    :key="index"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-600"
                                >
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ getNombreEquipoTrabajo(equipo.equipo_trabajo_id) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-300">
                                        {{ equipo.estado }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap">
                                        <button
                                            @click="eliminarEquipoTrabajo(index)"
                                            class="text-red-600 transition-colors duration-200 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit button with improved styling -->
                    <div class="animate-fadeIn mt-8 flex justify-center sm:justify-end" style="animation-delay: 0.8s">
                        <button
                            type="submit"
                            @click="submit_create"
                            :class="[
                                'flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-base font-medium text-white shadow-md transition-all duration-300 focus:ring-2 focus:ring-offset-2 focus:outline-none',
                                props.isCreate
                                    ? 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:ring-blue-500 dark:from-blue-700 dark:to-indigo-700 dark:hover:from-blue-800 dark:hover:to-indigo-800'
                                    : 'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:ring-green-500 dark:from-green-700 dark:to-emerald-700 dark:hover:from-green-800 dark:hover:to-emerald-800',
                                datas.isLoad ? 'cursor-not-allowed opacity-75' : 'hover:scale-105',
                            ]"
                            :disabled="datas.isLoad"
                        >
                            <span>{{ props.isCreate ? 'Crear' : 'Actualizar' }} {{ model_path }}</span>
                            <DiamondPlus v-if="props.isCreate" class="h-5 w-5 text-white" />
                            <Pencil v-else class="h-5 w-5 text-white" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Add custom transitions and animations */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Fade in animation for form elements */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.4s ease-in-out forwards;
}

/* Spin animation for loading indicator */
@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

/* Form group hover effects */
.form-group:hover label:not(.text-red-600):not(.dark\:text-red-400) {
    color: #4f46e5;
}

.dark .form-group:hover label:not(.text-red-600):not(.dark\:text-red-400) {
    color: #818cf8;
}

/* Input focus styles */
input:focus,
select:focus {
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
    outline: 2px solid transparent;
    outline-offset: 2px;
}

.dark input:focus,
.dark select:focus {
    box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.2);
}

/* Error styles */
.label-error {
    color: #ef4444 !important;
}

.input-error {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
}

.dark .input-error {
    border-color: #f87171 !important;
    background-color: rgba(239, 68, 68, 0.1) !important;
}

/* Select element text color */
select {
    color: #000000 !important; /* Black text in light mode */
}

.dark select {
    color: #ffffff !important; /* White text in dark mode */
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .form-group {
        margin-bottom: 1.5rem;
    }

    input,
    select {
        padding: 0.75rem;
        font-size: 1rem;
    }

    button {
        width: 100%;
    }
}

/* Custom scrollbar for better visual appeal */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}

select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    color: #111827 !important; /* text-gray-900 */
    background-color: #ffffff !important; /* bg-white */
}

.dark select {
    color: #f3f4f6 !important; /* text-gray-200 */
    background-color: #374151 !important; /* bg-gray-700 */
}

/* Select element text color */
select,
select option {
    color: #000000 !important; /* Black text in light mode */
}

.dark select,
.dark select option {
    color: #ffffff !important; /* White text in dark mode */
}

select option {
    color: #111827 !important; /* text-gray-900 */
    background-color: #ffffff !important; /* bg-white */
}

.dark select option {
    color: #f3f4f6 !important; /* text-gray-200 */
    background-color: #374151 !important; /* bg-gray-700 */
}

select option:checked {
    color: #111827 !important;
    background-color: #e5e7eb !important; /* bg-gray-200 */
}

.dark select option:checked {
    color: #f3f4f6 !important;
    background-color: #4b5563 !important; /* bg-gray-600 */
}
</style>
