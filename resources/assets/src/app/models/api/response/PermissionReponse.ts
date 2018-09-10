

export class Permisson {
    id: Number;
    name: string;
    display_name: string;
    description: string;
    created_at: string;
    updated_at: string;
}
export interface listPermissonRepo {
    status: string;
    message: string;
    screen_categories: ScreenCategory[];
}
export interface ScreenCategory {
    id: Number;
    name: string;
    display_name: string;
    description: string;
    created_at: string;
    updated_at: string;
    screens: ScreenPer[];
}
export interface ScreenPer {
    id: Number;
    name: string;
    display_name: string;
    description: string;
    screen_category_id: Number;
    created_at: string;
    updated_at: string;
    permissions: Permisson[];
}
