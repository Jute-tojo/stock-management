export type Client = {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    address: string;
};

export type ClientPaginate = {
    data: Client[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
};

export type ClientFormData = {
    name: string;
    email: string;
    phone: string;
    address: string;
};
