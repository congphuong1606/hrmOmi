export class Session {
    start_datetime: number;
    end_datetime: string;
    trainer: string;
    supporter: string;
    content: string;
    user_ids: number[];

    constructor() {

    }
}

export class CourseRepose {
    other_category_id: number;
    description: string;
    room: string;

    constructor() {

    }
}

export class UserCourseRepose {
    id: number;
    name: string;
    email: string;
    department: string;
    job_status: string;
    position: string;
    is_selected: boolean;

    constructor() {

    }
}

export class Training {
    id: number;
    name: string;
    user_id: number;
    email: string;
    score: number;
    presence: string;
    sessions: SessionPresence[];

    constructor() {

    }
}

export class CourseInListRepose {
    id: number;
    course_name: string;
    status: boolean;
    room_name: string;
    sessions_number: string;
    description: string;
    current_order: string;
    start_date: string;
    end_date: string;

    constructor() {
    }
}


export class OneCourse {
    course_category_id: number;
    description: string;
    room_category_id: number;
    sessions: Session[];

    constructor() {
    }
}

export class OneCourseForUser {
    id: number;
    course_name: string;
    room_name: string;
    description: string;
    current_order: string;
    status: boolean;
    score: string;
    required_session_number: number;
    sessions_number: number;
    sessions: SessionForUser[];

    constructor() {
    }
}

export class SessionForUser {
    id: number;
    start_datetime: string;
    end_datetime: string;
    trainer: string;
    supporter: string;
    content: string;
    required_session: boolean;
    presence: number;

    constructor() {
    }
}

export class SessionQr {
    id: number;
    qr_code: string;
    start_datetime: string;
    end_datetime: string;

    constructor() {
    }
}

export class CoreOfUsers {
    id: number;
    name: string;
    email: string;
    score: string;
    presence: string;


    constructor() {
    }
}

export class SessionPresence {
    id: number;
    start_datetime: string;
    end_datetime: string;
    presence: number;

    constructor() {
    }
}


