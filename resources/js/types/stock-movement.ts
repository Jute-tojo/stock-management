export type StockMovement = {
    id: number;
    product_id: number;
    user_id: number;
    type: 'in' | 'out' | 'initial';
    quantity: number;
    notes: string | null;
    created_at: string;
    product: { id: number; name: string; sku: string } | null;
    user: { id: number; name: string } | null;
};

export type StockMovementPaginate = {
    data: StockMovement[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
};

export type StockMovementFormData = {
    product_id: number | null;
    type: 'in' | 'out';
    quantity: number;
    notes: string;
};
