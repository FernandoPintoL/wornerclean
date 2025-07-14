<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, Mail, Lock, LogIn } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const isLoading = ref(false);

const submit = () => {
    isLoading.value = true;
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
            isLoading.value = false;
        },
    });
};
</script>

<template>
    <AuthBase title="Bienvenido de nuevo" description="Ingrese sus credenciales para acceder a su cuenta">
        <Head title="Iniciar sesión" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <Card class="w-full shadow-lg border-green-100 dark:border-green-900 overflow-hidden">
            <CardHeader class="bg-gradient-to-r from-green-500 to-teal-600 text-white">
                <CardTitle class="text-xl font-bold flex items-center justify-center">
                    <LogIn class="mr-2 h-6 w-6" />
                    Acceso al Sistema
                </CardTitle>
                <CardDescription class="text-teal-100">
                    Ingrese su correo electrónico y contraseña
                </CardDescription>
            </CardHeader>

            <CardContent class="pt-6">
                <form @submit.prevent="submit" class="flex flex-col gap-6">
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="email" class="text-gray-700 dark:text-gray-300 font-medium">Correo Electrónico</Label>
                            <div class="relative">
                                <Mail class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                                <Input
                                    id="email"
                                    type="email"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    autocomplete="email"
                                    v-model="form.email"
                                    placeholder="email@example.com"
                                    class="pl-10 bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500"
                                />
                            </div>
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="grid gap-2">
                            <div class="flex items-center justify-between">
                                <Label for="password" class="text-gray-700 dark:text-gray-300 font-medium">Contraseña</Label>
                                <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" :tabindex="5">
                                    ¿Olvidó su contraseña?
                                </TextLink>
                            </div>
                            <div class="relative">
                                <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                                <Input
                                    id="password"
                                    type="password"
                                    required
                                    :tabindex="2"
                                    autocomplete="current-password"
                                    v-model="form.password"
                                    placeholder="Contraseña"
                                    class="pl-10 bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-700 focus:border-teal-500 focus:ring-teal-500"
                                />
                            </div>
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="flex items-center">
                            <Label for="remember" class="flex items-center space-x-3 cursor-pointer">
                                <Checkbox id="remember" v-model="form.remember" :tabindex="3" class="text-teal-600" />
                                <span class="text-sm text-gray-600 dark:text-gray-400">Recordar mis datos</span>
                            </Label>
                        </div>
                    </div>

                    <Button
                        type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-md transition-colors duration-300 flex items-center justify-center"
                        :tabindex="4"
                        :disabled="form.processing || isLoading"
                    >
                        <LoaderCircle v-if="form.processing || isLoading" class="mr-2 h-5 w-5 animate-spin" />
                        <LogIn v-else class="mr-2 h-5 w-5" />
                        Iniciar sesión
                    </Button>
                </form>
            </CardContent>

            <CardFooter class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex justify-center py-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Worner Clean © {{ new Date().getFullYear() }} - Servicios de Limpieza Profesional - Tecnologia Web Grupo 18SC
                </p>
            </CardFooter>
        </Card>
    </AuthBase>
</template>
