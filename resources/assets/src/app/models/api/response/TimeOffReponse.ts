export class TimeOff {
    id: number;
    from_time: string;
    to_time: string;
    date: string;
    time: string;
    status: number;
    reason: string;
    approved: number;
    approved_reason: string;
    backup_person: string;
    team_leader: string;
    project_manger: string;
    detailed_reason: string;
    updated_at: string;

    constructor() {
    }
}

// list time off
export interface ListTimeOffRepo {
    status: string;
    message: string;
    time_off: TimeOff[];
    pagination: Pagination;
}

export class ShowAnTimeOff {
    id: number;
    employee_id: number;
    employee_full_name: string;
    start_datetime: string;
    end_datetime: string;
    status: number;
    approved: number;
    approved_reason: string;
    reason: string;
    detailed_reason: string;
    project_manger: string;
    team_leader: string;
    backup_person: string;
    file_id: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    flow_type: number;
    forget_type: any;

    constructor() {
    }
}

// an time off
export interface TimeOffRepo {
    status: string;
    message: string;
    time_off: TimeOff;
}

// list day off
export interface ListDayOffRepo {
    status: string;
    message: string;
    time_off: DayOff[];
    pagination: Pagination;
}

// an day off
export interface DayOffRepo {
    status: string;
    message: string;
    time_off: DayOff;
}

// list TimeOff Approver off
export interface ListTimeOffApproverRepo {
    status: string;
    message: string;
    times_off: TimeOffApprover[];
    pagination: Pagination;
}

export class DayOff {
    id: number;
    from_date: string;
    to_date: string;
    number_of_days: string;
    status: number;
    approved_reason: string;
    reason: string;
    approved: number;
    backup_person: string;
    team_leader: string;
    project_manger: string;
    detailed_reason: string;
    updated_at: string;
    forget_type: any;

    constructor() {
    }
}

export class TimeOffApprover {
    id: number;
    info: Info;
    approved_reason: string;
    from_time: string;
    to_time: string;
    status: number;
    approved: number;
    backup_person: string;
    number_of_times: string;
    detailed_reason: string;
    forget_type: any;
    reason: string;

    constructor() {
    }
}

export class Info {
    full_name: string;
    email: string;
    birth_day: string;

    constructor() {
    }
}

export class Pagination {
    total: number;
    per_page: string;
    current_page: number;
    last_page: number;

    constructor() {
    }
}

export class TimeOffExcelFile {
    id: number;
    name: string;
    user_id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    constructor() {
    }
}




