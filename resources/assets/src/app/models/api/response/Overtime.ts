export class Overtime {
    employee_id: number;
    start_datetime: string;
    end_datetime: string;
    proposer: string;
    approver: string;
    work_content: string;
    approved: number;
    updated_at: string;
    created_at: string;
    id: number;

    constructor() {

    }
}

export class OvertimeApprover {
    id: number;
    info: Info;
    from_time: string;
    to_time: string;
    number_of_times: string;
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