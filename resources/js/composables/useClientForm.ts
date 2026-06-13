import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { store, update, destroy as destroyRoute } from '@/routes/clients';
import type { Client, ClientFormData } from '@/types';

const emptyForm: ClientFormData = {
    name: '',
    email: '',
    phone: '',
    address: '',
};

export const useClientForm = () => {
    const isEdit = ref(false);
    const editingClient = ref<Client | null>(null);

    const form = useForm<ClientFormData>({ ...emptyForm });

    const resetForm = () => {
        isEdit.value = false;
        editingClient.value = null;
        form.reset();
        form.clearErrors();
    };

    const startEdit = (client: Client) => {
        isEdit.value = true;
        editingClient.value = client;
        form.reset();
        form.clearErrors();
        form.name = client.name;
        form.email = client.email;
        form.phone = client.phone ?? '';
        form.address = client.address;
    };

    const submit = () => {
        if (isEdit.value && editingClient.value) {
            form
                .transform((data) => ({ ...data, _method: 'PATCH' }))
                .post(update.url(editingClient.value.id), {
                    preserveScroll: true,
                    onSuccess: () => resetForm(),
                    onFinish: () => form.transform((data) => data),
                });
        } else {
            form.post(store.url(), {
                preserveScroll: true,
                onSuccess: () => resetForm(),
            });
        }
    };

    const destroy = (client: Client) => {
        router.delete(destroyRoute.url(client.id), {
            preserveScroll: true,
        });
    };

    return {
        form,
        isEdit,
        editingClient,
        resetForm,
        startEdit,
        submit,
        destroy,
    };
};
