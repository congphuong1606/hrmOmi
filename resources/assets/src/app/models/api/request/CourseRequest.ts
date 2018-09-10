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

export class CourseRequest {
    other_category_id: number;
    description: string;
    room: string;
    sessions: Session[];
    constructor() {
    }
}