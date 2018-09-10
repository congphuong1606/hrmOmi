export class EmpployeeAttachFile {
    id: number;
    name: string;
    saved_name: string;
    deleted: boolean;
    constructor() {
        this.id = 0;
        this.name = '';
        this.saved_name = '';
        this.deleted = false;
    }
}

export class SpecializedSkills {
    id: number;
    name: string;
    description: string;
    constructor() {
        this.id = 0;
        this.name = '';
        this.description = '';
    }
}

export class Employee {
    id: number;
    user_id: number;
    full_name: string;
    department_id: number;
    position_id: number;
    working_status_id: number;
    late_reason_id: number;
    job_status_id: number;
    gender: number;
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
    bank_user_name: string;
    bank_branch: string;
    phone_number: string;
    chatwork_account: string;
    skype_account: string;
    facebook_link: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    department: any;
    position: any;
    late_reason: any;
    working_status: any;
    checked: boolean;
    employee_code: string;
    attendance_code: string;
    email: string;
    avatar: string;
    update_date: string;
    check_in_date: string;
    training_date: string;
    official_date: string;
    personal_email: string;
    contact_user: string;
    direct_manager: any;
    attach_files: EmpployeeAttachFile[];
    specialized_skills: SpecializedSkills[];
    late_reasons: LateReason[];
    employee_late_reasons: any[];
    project_managers: any;
    is_project_manager: number;
    direct_manager_name: string;
    constructor() {
        this.id = 0;
        this.user_id = 0;
        this.full_name = '';
        this.department_id = 0;
        this.position_id = 0;
        this.late_reason_id = 0;
        this.job_status_id = 0;
        this.gender = 0;
        this.job_status = {
            name: ''
        };
        this.department = {
            name: ''
        };
        this.position = {
            name: ''
        };
        this.late_reason = {
            name: ''
        };
        this.working_status = {
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
        this.bank_name = '';
        this.bank_user_name = '';
        this.bank_branch = '';
        this.phone_number = '';
        this.chatwork_account = '';
        this.skype_account = '';
        this.facebook_link = '';
        this.created_at = '';
        this.updated_at = '';
        this.deleted_at = '';
        this.checked = false;
        this.avatar = '';
        this.update_date = '';
        this.check_in_date = '';
        this.training_date = '';
        this.official_date = '';
        this.personal_email = '';
        this.contact_user = '';
        this.working_status_id = 0;
        this.attach_files = [];
        this.specialized_skills = [];
        this.direct_manager = null;
        this.project_managers = [];
        this.is_project_manager = 0;
        this.direct_manager_name = null;
        this.employee_late_reasons = [];
        this.late_reasons = [];;
    }
}

export class EmployeeChange {
    id: number;
    user_id: number;
    full_name: string;
    department_id: number;
    position_id: number;
    job_status_id: number;
    working_status_id: number;
    job_status: any;
    late_reason: any;
    late_reason_id: number;
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
    working_status: any;
    employee_code: string;
    attendance_code: string;
    update_date = '';
    check_in_date = '';
    training_date = '';
    official_date = '';
    personal_email = '';
    email: string;
    avatar: string;
    employee: Employee;
    constructor() {
        this.id = 0;
        this.user_id = 0;
        this.full_name = '';
        this.department_id = 0;
        this.late_reason_id = 0;
        this.position_id = 0;
        this.job_status_id = 0;
        this.job_status = {
            name: ''
        };
        this.department = {
            name: ''
        };
        this.position = {
            name: ''
        };
        this.late_reason = {
            name: ''
        };
        this.working_status = {
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
        this.bank_name = '';
        this.phone_number = '';
        this.chatwork_account = '';
        this.skype_account = '';
        this.facebook_link = '';
        this.created_at = '';
        this.updated_at = '';
        this.deleted_at = '';
        this.checked = false;
        this.avatar = '';
        this.update_date = '';
        this.check_in_date = '';
        this.training_date = '';
        this.official_date = '';
        this.personal_email = '';
        this.working_status_id = 0;
        this.employee = new Employee();
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

export interface EmployeeChangeRes {
    status: string;
    message: string;
    employee: EmployeeChange;
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

export class EmployeeExcelDepartment {
    id: number;
    name: string;
    department_id: number;
    department: Department;
    created_at: string;
    updated_at: string;
}

export class EmployeeExcelJobStatus {
    id: number;
    name: string;
    job_status_id: number;
    job_status: JobStatus;
    created_at: string;
    updated_at: string;
}

export class EmployeeExcelPosition {
    id: number;
    name: string;
    position_id: number;
    position: Position;
    created_at: string;
    updated_at: string;
}

export interface EmployeeExcelDepartmentRes {
    status: string;
    message: string;
    departments: EmployeeExcelDepartment[];
}

export interface EmployeeExcelJobStatusRes {
    status: string;
    message: string;
    job_status: EmployeeExcelJobStatus[];
}

export interface EmployeeExcelPositionRes {
    status: string;
    message: string;
    positions: EmployeeExcelPosition[];
}

export class User {
    id: number;
    name: string;
    email: string;
}

export class EmployeeExcelFile {
    id: number;
    name: string;
    user: User;
    created_at: string;
    updated_at: string;
}

export interface EmployeeExcelFileRes {
    status: string;
    message: string;
    files: EmployeeExcelFile[];
}

export class EmployeeExcelFileData {
    id: number;
    name: string;
    job_status: string;
    position: string;
    birthday: string;
    phone: string;
    personal_email: string;
    email: string;
    skype: string;
    facebook: string;
    checkin_date: string;
    training_start_date: string;
    employee_start_date: string;
    fingerprint_id: string;
    identification_number: string;
    identification_date: string;
    identification_place: string;
    tax_code: string;
    permanent_address: string;
    temporary_address: string;
    bank_number: string;
    note: string;
    japanese_certificate: string;
    employee_id: string;
    employee_excel_file_id: string;
    is_accepted: string;
    department: string;
    is_duplicate: string;
    created_at: string;
    updated_at: string;
    is_checked = false;
}

export class EmployeeExcelFileDetail {
    id: number;
    name: string;
    user: User;
    created_at: string;
    updated_at: string;
    status: number;
    data: EmployeeExcelFileData[];
    constructor() {

    }
}

export interface EmployeeExcelFileDetailRes {
    status: string;
    message: string;
    file: EmployeeExcelFileDetail;
}

export interface EmployeeExcelFileParseRes {
    status: string;
    message: string;
    file: EmployeeExcelFileDetail;
}


export class Department {
    id: number;
    name: string;
    display_name: string;
    description: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    constructor() {
        this.id = 0;
        this.name = '';
        this.display_name = '';
        this.description = '';
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
    }
}

export interface DepartmentRes {
    status: string;
    message: string;
    departments: Department[];
}

export class LateReason {
    id: number;
    name: string;
    description: string;
    start_morning: string;
    end_morning: string;
    start_afternoon: string;
    end_afternoon: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    constructor() {
        this.id = 0;
        this.name = 'Không';
        this.description = 'Không';
        this.start_morning = '';
        this.end_morning = '';
        this.start_afternoon = '';
        this.end_afternoon = '';
        this.created_at = '';
        this.updated_at = '';
        this.deleted_at = '';
    }
}

export interface LateReasonRes {
    status: string;
    message: string;
    late_reasons: LateReason[];
}


export class Position {
    id: number;
    name: string;
    display_name: string;
    description: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    
    constructor() {
        this.id = 0;
        this.name = '';
        this.display_name = '';
        this.description = '';
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
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
    deleted_at: string;

    constructor() {
        this.id = 0;
        this.name = '';
        this.display_name = '';
        this.description = '';
        this.created_at = null;
        this.updated_at = null;
        this.deleted_at = null;
    }
}

export interface JobStatusRes {
    status: string;
    message: string;
    jobs_status: JobStatus[];
}

export class WorkingStatus {
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

export interface WorkingStatusRes {
    status: string;
    message: string;
    working_status: WorkingStatus[];
}

export interface ChangeRes {
    status: string;
    message: string;
    employees: Employee[];
    pagination: Pagination;
}

export class Skill {
    id: number;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
}

export interface SkillsRes {
    status: string;
    message: string;
    specialized_skills: Skill[];
}

export class TimeKeepingExcelFile {
    id: number;
    name: string;
    user: User;
    created_at: string;
    updated_at: string;
}

export interface TimeKeepingExcelFileRes {
    status: string;
    message: string;
    files: TimeKeepingExcelFile[];
}

export class TimeKeepingExcelFileData {
    id: number;
    name: string;
    job_status: string;
    position: string;
    birthday: string;
    phone: string;
    personal_email: string;
    email: string;
    skype: string;
    facebook: string;
    checkin_date: string;
    training_start_date: string;
    employee_start_date: string;
    fingerprint_id: string;
    identification_number: string;
    identification_date: string;
    identification_place: string;
    tax_code: string;
    permanent_address: string;
    temporary_address: string;
    bank_number: string;
    note: string;
    japanese_certificate: string;
    employee_id: string;
    employee_excel_file_id: string;
    is_accepted: string;
    department: string;
    is_duplicate: string;
    created_at: string;
    updated_at: string;
    is_checked = false;
    showing = false;
    time_on_id: any;
}

export class TimeKeepingExcelFileDetail {
    id: number;
    name: string;
    user: User;
    created_at: string;
    updated_at: string;
    status: number;
    data: TimeKeepingExcelFileData[];
    constructor() {

    }
}

export interface TimeKeepingExcelFileDetailRes {
    status: string;
    message: string;
    file: TimeKeepingExcelFileDetail;
}

export interface TimeKeepingExcelFileParseRes {
    status: string;
    message: string;
    file: TimeKeepingExcelFileDetail;
}

export class TimeKeepingData {
    id: number;
    name: string;
    job_status: string;
    position: string;
    birthday: string;
    phone: string;
    personal_email: string;
    email: string;
    skype: string;
    facebook: string;
    checkin_date: string;
    training_start_date: string;
    employee_start_date: string;
    fingerprint_id: string;
    identification_number: string;
    identification_date: string;
    identification_place: string;
    tax_code: string;
    permanent_address: string;
    temporary_address: string;
    bank_number: string;
    note: string;
    japanese_certificate: string;
    employee_id: string;
    employee_excel_file_id: string;
    is_accepted: string;
    department: string;
    is_duplicate: string;
    created_at: string;
    updated_at: string;
    is_checked = false;
}

export class TimeKeeping {
    id: number;
    name: string;
    employee: Employee;
    created_at: string;
    updated_at: string;
    data: TimeKeepingData[];
    constructor() {

    }
}

export interface TimeKeepinglRes {
    status: string;
    message: string;
    time_ons: TimeKeeping;
}