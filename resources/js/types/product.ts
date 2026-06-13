export type Product = {
    id: number;
    name: string;
    sku: string;
    image: string | null;
    image_url: string | null;
    description: string | null;
    price: number;
    quantity: number;
    unit: string;
    category: { id: number; name: string } | null;
};

export type ProductPaginate = {
    data: Product[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
};

export type ProductFormData = {
    category_id: number | null;
    name: string;
    sku: string;
    image: File | null;
    description: string;
    price: number;
    quantity: number;
    unit: string;
};
