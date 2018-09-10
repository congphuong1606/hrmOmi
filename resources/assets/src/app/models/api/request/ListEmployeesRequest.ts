export class Employee {
    id: number;
    user_id: number;
    full_name: string;
    department_id: number;
    position_id: number;
    job_status: any;
    birth_day: string;
    identification_number: string;
    identification_date: string;
    identification_place_of: string;
    tax_code: string;
    permanent_address: string;
    temporary_address: string;
    bank_number: string;
    bank_name: string;
    phone_number: string;
    chatwork_account: string;
    skype_account: string;
    facebook_link: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    department: any;
    position: any;
    checked: boolean;
    constructor() {
        this.id = 0;
        this.user_id = 0;
        this.full_name = '';
        this.department_id = 0;
        this.position_id = 0;
        this.job_status = {
            name: ''
        };
        this.department = {
            name: ''
        };
        this.position = {
            name: ''
        };
        this.birth_day = '';
        this.identification_number = '';
        this.identification_date = '';
        this.identification_place_of = '';
        this.tax_code = '';
        this.permanent_address = '';
        this.temporary_address = '';
        this.bank_number = '';
        this. bank_name = '';
        this. phone_number = '';
        this. chatwork_account = '';
        this.skype_account = '';
        this.facebook_link = '';
        this. created_at = '';
        this. updated_at = '';
        this.deleted_at = '';
        this.checked = false;
    }
}
export class Pagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    constructor() {
        this.current_page = 0;
        this.last_page = 0;
        this.per_page = 0;
        this.total = 0;
    }
}

export interface Employees {
    status: string;
    message: string;
    employees: Employee[];
    pagination: Pagination;
}

export interface EmployeeRes {
    status: string;
    message: string;
    employee: Employee;
}

export class Role {
    id: number;
    name: string;
    display_name: string;
    description: string;
    created_at: string;
    updated_at: string;
}

export interface RolesRes {
    status: string;
    message: string;
    roles: Role[];
}


export class Department {
    id: number;
    name: string;
    display_name: string;
    description: string;
    created_at: string;
    updated_at: string;
    constructor() {
        this.id = 0;
        this.name = '';
        this.display_name = '';
        this.description = '';
        this.created_at = null;
        this.updated_at = null;
    }
}

export interface DepartmentRes {
    status: string;
    message: string;
    departments: Department[];
}


export class Position {
    id: number;
    name: string;
    display_name: string;
    description: string;
    created_at: string;
    updated_at: string;

    constructor() {
        this.id = 0;
        this.name = '';
        this.display_name = '';
        this.description = '';
        this.created_at = null;
        this.updated_at = null;
    }
}

export interface PositionRes {
    status: string;
    message: string;
    positions: Position[];
}

export class JobStatus {
    id: number;
    name: string;
    display_name: string;
    description: string;
    created_at: string;
    updated_at: string;

    constructor() {
        this.id = 0;
        this.name = '';
        this.display_name = '';
        this.description = '';
        this.created_at = null;
        this.updated_at = null;
    }
}

export interface JobStatusRes {
    status: string;
    message: string;
    jobs_status: JobStatus[];
}

export interface ChangeRes {
    status: string;
    message: string;
    employees: Employee[];
}


export class SearchEmployeeFormRequest {
    name: string;
    id: string;
    department: string;
    position: string;
    job_status: string;
    description: string;
    working_status: string;
    limit: string;
    page: string;
    advanced_search: string;
    search_department: string;
    search_position: string;
    search_job_status: string;
    search_working_status: string;
    constructor() {
        this.name = '';
        this.id = '';
        this.department = '';
        this.position = '';
        this.job_status = '';
        this.description = '';
        this.working_status = '';
        this.limit = '';
        this.page = '';
        this.advanced_search = '';
        this.search_department = '';
        this.search_position = '';
        this.search_job_status = '';
        this.search_working_status = '';
    }
}

export class SearchEmployeeUpdateHistoryFormRequest {
    limit: string;
    page: string;

    constructor() {
        this.limit = '';
        this.page = '';
    }
}


