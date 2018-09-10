export class ScreenCategory {
    name: string;
    display_name: string;
    description: string;
    updated_at: string;
    created_at: string;
    id: Number;
    constructor() {
    }
}

export class Screen {
    id: Number;
    name: string;
    url: string;
    display_name: string;
    description: string;
    screen_category_id: string;
    updated_at: string;
    created_at: string;
    category: ScreenCategory;
    constructor() {
    }
}
export interface ScreenCategoryRepo {
    status: string;
    message: string;
    screen_category: ScreenCategory;
}
export interface ScreenRepo {
    status: string;
    message: string;
    screen: Screen;
}
export interface ListScreenRepo {
    status: string;
    message: string;
    screens: Screen[];
}
export interface ListScrCategory {
    status: string;
    message: string;
    screen_categories: ScreenCategory[];
}
export class Url {
    id: number;
    url: string;
    constructor() {

    }
}