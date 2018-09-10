import { Injectable } from '@angular/core';
import { Http, Response, Headers } from '@angular/http';
import 'rxjs/add/operator/toPromise';
import * as jwt_decode from 'jwt-decode';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Router } from '@angular/router';
import { HttpHeaders } from '@angular/common/http';
import { Employees, Employee, EmployeeRes, RolesRes, DepartmentRes, PositionRes, JobStatusRes, ChangeRes, WorkingStatus, WorkingStatusRes, EmployeeChange, EmployeeChangeRes, SkillsRes, EmployeeExcelDepartmentRes, EmployeeExcelFileRes, EmployeeExcelFileDetailRes, EmployeeExcelJobStatusRes, EmployeeExcelPositionRes, TimeKeepingExcelFileRes, TimeKeepingExcelFileDetailRes, LateReasonRes } from '../models/api/response/ListEmployeesResponse';
import { SearchEmployeeFormRequest, SearchEmployeeUpdateHistoryFormRequest } from '../models/api/request/ListEmployeesRequest';
import { ContentType } from '@angular/http/src/enums';
import { Observable } from 'rxjs/Observable';
import "rxjs/Rx";

export const TOKEN_NAME: string = 'jwt_token';


@Injectable()
export class PersonnelService {

    private urlRoot = 'api/employees/';
    headers = new HttpHeaders({
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
    })

    httpOptions = {
        headers: new HttpHeaders({
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
        })
    };


    constructor(private http: HttpClient, private router: Router) {
    }

    getListEmployees(searchForm: SearchEmployeeFormRequest) {
        const formRequest = {};
        let params = new HttpParams();
        if (searchForm.id !== null) {
            params = params.set('id', searchForm.id);
        }
        if (searchForm.name !== null) {
            params = params.set('name', searchForm.name);
        }
        if (searchForm.page !== null) {
            params = params.set('page', searchForm.page);
        }

        if (searchForm.advanced_search === 'true') {
            if (searchForm.department !== null) {
                params = params.set('department', searchForm.department);
            }
            if (searchForm.position !== null) {
                params = params.set('position', searchForm.position);
            }
            if (searchForm.job_status !== null) {
                params = params.set('job_status', searchForm.job_status);
            }
            if (searchForm.working_status !== null) {
                params = params.set('working_status', searchForm.working_status);
            }
            if (searchForm.limit !== null) {
                params = params.set('limit', searchForm.limit);
            }
            params = params.set('advanced_search', searchForm.advanced_search);

        }
        const httpOptions = {
            headers: this.headers,
            params: params
        };

        return this.http.get<Employees>('api/employees', httpOptions);
    }

    deletePersonnel(id) {
        return this.http.delete<Employees>(this.urlRoot + id, this.httpOptions);
    }

    getEmployee(id) {
        return this.http.get<EmployeeRes>(this.urlRoot + id, this.httpOptions);
    }

    getListRoles() {
        return this.http.get<RolesRes>('api/roles', this.httpOptions);
    }

    getListDeparments() {
        return this.http.get<DepartmentRes>('api/departments', this.httpOptions);
    }

    getListLateReasons() {
        return this.http.get<LateReasonRes>('api/late_reasons', this.httpOptions);
    }

    getListPositions() {
        return this.http.get<PositionRes>('api/positions', this.httpOptions);
    }

    getListJobStatus() {
        return this.http.get<JobStatusRes>('api/job_status', this.httpOptions);
    }

    getListWorkingStatus() {
        return this.http.get<WorkingStatusRes>('api/working_status', this.httpOptions);
    }

    getListSkills() {
        return this.http.get<SkillsRes>('api/specialized_skills', this.httpOptions);
    }

    getListChange(searchForm: SearchEmployeeUpdateHistoryFormRequest) {
        const formRequest = {};
        let params = new HttpParams();
        if (searchForm.page !== null) {
            params = params.set('page', searchForm.page);
        }
        if (searchForm.limit !== null) {
            params = params.set('limit', searchForm.limit);
        }

        const httpOptions = {
            headers: this.headers,
            params: params
        };

        return this.http.get<ChangeRes>('api/history/employees', httpOptions);
    }

    getEmployeeChange(id) {
        return this.http.get<EmployeeChangeRes>('api/history/employees/' + id, this.httpOptions);
    }

    approveListChange(ids: number[]) {
        return this.http.post('api/history/employees/approve', { ids: ids }, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    rejectListChange(ids: number[]) {
        return this.http.post('api/history/employees/reject', { ids: ids }, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    approveChange(id: number) {
        return this.http.post('api/history/employees/' + id + '/approve', {}, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    rejectChange(id: number) {
        return this.http.post('api/history/employees/' + id + '/reject', {}, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    addEmployee(data) {
        let formData = new FormData();
        // Object.keys(data).forEach(key => {
        //     if (key === 'attach_files') {
        //         for (let i = 0; i < data[key].length; i++) {
        //             formData.append(key + '[]', data[key][i]);
        //         }
        //     } else {
        //         if (key === 'late_reasons') {
        //             for (let i = 0; i < data[key].length; i++) {
        //                 formData.append(key + '[' + i + '][""]', data[key][i]);
        //             }
        //         } else {
        //             if (data[key] !== null) {
        //                 formData.append(key, data[key]);
        //             } else {
        //                 formData.append(key, '');
        //             }
        //         }
        //     }
        // });
        formData = this.convertModelToFormData(data)
        return this.http.post('api/employees', formData, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    updateEmployee(id, data) {
        let formData = new FormData();
        // console.log(data);
        // Object.keys(data).forEach(key => {
        //     if (key === 'attach_files') {
        //         for (let i = 0; i < data[key].length; i++) {
        //             formData.append(key + '[]', data[key][i]);
        //         }
        //     } else {
        //         if (key === 'late_reasons') {
        //             for (let i = 0; i < data[key].length; i++) {
        //                 formData.append(key + '[' + i + '][""]', data[key][i]);
        //             }
        //         } else {
        //             if (data[key] !== null) {
        //                 formData.append(key, data[key]);
        //             } else {
        //                 formData.append(key, '');
        //             }
        //         }
        //     }
        // });
        formData = this.convertModelToFormData(data)
        return this.http.post('api/employees/' + id + '/update', formData, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    convertModelToFormData(val, formData = new FormData(), namespace = '') {
        if ((typeof val !== 'undefined') && (val !== null)) {
            if (val instanceof Date) {
                formData.append(namespace, val.toISOString());
            } else if (val instanceof Array) {
                for (let i = 0; i < val.length; i++) {
                    this.convertModelToFormData(val[i], formData, namespace + '[' + i + ']');
                }
            } else if (typeof val === 'object' && !(val instanceof File)) {
                if (val instanceof FileList) {
                    for (let i = 0; i < val.length; i++) {
                        formData.append(namespace + '[]', val[i]);
                    }
                } else {
                    for (let propertyName in val) {
                        if (val.hasOwnProperty(propertyName)) {
                            this.convertModelToFormData(val[propertyName], formData, namespace ? namespace + '[' + propertyName + ']' : propertyName);
                        }
                    }
                }
            } else if (val instanceof File) {
                formData.append(namespace, val);
            } else {
                formData.append(namespace, val.toString());
            }
        }
        return formData;
    }

    changePassword(data) {
        return this.http.post('api/change-password', data, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    uploadFilePersonnel(data) {
        const formData = new FormData();
        Object.keys(data).forEach(key => {
            formData.append(key, data[key]);
        });
        return this.http.post<EmployeeExcelFileRes>('api/employee-excel/files/upload', formData, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    getListEmployeeExcelDepartments() {
        return this.http.get<EmployeeExcelDepartmentRes>('api/employee-excel/departments', this.httpOptions);
    }

    getListEmployeeExcelJobStatus() {
        return this.http.get<EmployeeExcelJobStatusRes>('api/employee-excel/job_status', this.httpOptions);
    }

    getListEmployeeExcelPositions() {
        return this.http.get<EmployeeExcelPositionRes>('api/employee-excel/positions', this.httpOptions);
    }

    getListEmployeeExcelFiles() {
        return this.http.get<EmployeeExcelFileRes>('api/employee-excel/files', this.httpOptions);
    }

    getEmployeeExcelFile(id) {
        return this.http.get<EmployeeExcelFileDetailRes>('api/employee-excel/files/' + id, this.httpOptions);
    }

    parseEmployeeExcelFile(id) {
        return this.http.get<EmployeeExcelFileDetailRes>('api/employee-excel/files/' + id + '/parse', this.httpOptions);
    }

    applyEmployeeExcelFile(id, ids: number[]) {
        return this.http.post<any>('api/employee-excel/files/' + id + '/apply', { ids: ids }, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }


    applyEmployeeExcelDepartment(ids: number[]) {
        return this.http.post<any>('api/employee-excel/departments/apply', { ids: ids }, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    applyEmployeeExcelJobStatus(ids: number[]) {
        return this.http.post<any>('api/employee-excel/job_status/apply', { ids: ids }, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    applyEmployeeExcelPosition(ids) {
        return this.http.post<any>('api/employee-excel/positions/apply', { ids: ids }, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }


    uploadFileTimeKeeping(data) {
        const formData = new FormData();
        Object.keys(data).forEach(key => {
            formData.append(key, data[key]);
        });
        return this.http.post<TimeKeepingExcelFileRes>('api/time-on-excel/files/upload', formData, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    getListTimeKeepingExcelFiles() {
        return this.http.get<TimeKeepingExcelFileRes>('api/time-on-excel/files', this.httpOptions);
    }

    getTimeKeepingExcelFile(id) {
        return this.http.get<TimeKeepingExcelFileDetailRes>('api/time-on-excel/files/' + id, this.httpOptions);
    }

    parseTimeKeepingExcelFile(id) {
        return this.http.get<TimeKeepingExcelFileDetailRes>('api/time-on-excel/files/' + id + '/parse', this.httpOptions);
    }

    applyTimekeepingExcelFile(id, ids: number[]) {
        return this.http.post<any>('api/time-on-excel/files/' + id + '/apply', { ids: ids }, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    getTimeOn(month, employee_name, employee_code, department_id, job_status_id) {
        let params = new HttpParams();
        if (month !== null) {
            params = params.set('month', month);
        }
        if (employee_name !== null) {
            params = params.set('employee_name', employee_name);
        }

        if (employee_code !== null) {
            params = params.set('employee_code', employee_code);
        }

        if (department_id !== null) {
            params = params.set('department_id', department_id);
        }

        if (job_status_id !== null) {
            params = params.set('job_status_id', job_status_id);
        }

        const httpOptions = {
            headers: this.headers,
            params: params
        };

        return this.http.get<any>('api/time-on', httpOptions);
    }

    getTimeOnEmployee(month, employee_id) {
        let params = new HttpParams();
        if (month !== null) {
            params = params.set('month', month);
        }

        const httpOptions = {
            headers: this.headers,
            params: params
        };

        return this.http.get<any>('api/time-on/employee/' + employee_id, httpOptions);
    }

    calculatingTimeOn() {
        return this.http.post<any>('api/time-on/calculating', this.httpOptions);
    }

    getListTimeKeepingMonths() {
        return this.http.get<any>('api/time-on/months', this.httpOptions);
    }

    getTotalListTimeKeepingMonths() {
        return this.http.get<any>('api/time-on/total/months', this.httpOptions);
    }

    getTotalListTimeKeepingMonthsEmployee(id) {
        return this.http.get<any>('api/time-on/total/months/employee/' + id, this.httpOptions);
    }

    getTimeKeepingMonth(month, name, code, page) {
        let params = new HttpParams();
        if (month !== null) {
            params = params.set('month', month);
        }
        params = params.set('employee_name', name);
        params = params.set('employee_code', code);
        params = params.set('page', page);
        const httpOptions = {
            headers: this.headers,
            params: params
        };

        return this.http.get<any>('api/time-on/checkincheckout', httpOptions);
    }

    getCheckInCheckOut(id) {
        return this.http.get<any>('api/time-on/checkincheckout/' + id, this.httpOptions);
    }

    updateCheckInCheckOut(id, data) {
        return this.http.put<any>('api/time-on/checkincheckout/' + id, data, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    searchEmails(emails: Observable<string>) {
        return emails.debounceTime(400)
            .distinctUntilChanged()
            .switchMap(email => this.searchEmailExistCreate(email));
    }

    searchEmailExistCreate(data) {
        return this.http.post<any>('api/employees/validate/email-exist-create', data, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        }).delay(1000);
    }

    searchEmailExistUpdate(data) {
        return this.http.post<any>('api/employees/validate/email-exist-update', data, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        }).delay(1000);
    }

    searchEmployeeCodeExistCreate(data) {
        return this.http.post<any>('api/employees/validate/employee-code-exist-create', data, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        }).delay(1000);
    }

    searchEmployeeCodeExistUpdate(data) {
        return this.http.post<any>('api/employees/validate/employee-code-exist-update', data, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        }).delay(1000);
    }

    getListEmployeesResource(searchForm: any) {
        let params = new HttpParams();
        if (searchForm.employee_name !== null) {
            params = params.set('employee_name', searchForm.employee_name);
        }
        if (searchForm.direct_manager_name !== null) {
            params = params.set('direct_manager_name', searchForm.direct_manager_name);
        }
        if (searchForm.project_manager_name !== null) {
            params = params.set('project_manager_name', searchForm.project_manager_name);
        }
        if (searchForm.page !== null) {
            params = params.set('page', searchForm.page);
        }

        if (searchForm.advanced_search) {
            if (searchForm.department !== null) {
                params = params.set('department_id', searchForm.department_id);
            }
            if (searchForm.position !== null) {
                params = params.set('position_id', searchForm.position_id);
            }
            if (searchForm.job_status !== null) {
                params = params.set('job_status_id', searchForm.job_status_id);
            }
            if (searchForm.working_status !== null) {
                params = params.set('working_status_id', searchForm.working_status_id);
            }
            if (searchForm.limit !== null) {
                params = params.set('limit', searchForm.limit);
            }
        }
        const httpOptions = {
            headers: this.headers,
            params: params
        };

        return this.http.get<any>('api/employees/resource/employees', httpOptions);
    }

    getListDirectManagers() {
        return this.http.get<any>('api/employees/resource/direct-managers', this.httpOptions);
    }

    updateDirectManager(employee_id, data) {
        return this.http.put<any>('api/employees/resource/employees/' + employee_id + '/direct-manager', data, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    getListProjectManagers() {
        return this.http.get<any>('api/employees/resource/project-managers', this.httpOptions);
    }

    updateProjectManager(employee_id, data) {
        return this.http.put<any>('api/employees/resource/employees/' + employee_id + '/project-manager', data, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    getListAccumulatedYears() {
        return this.http.get<any>('api/time-on/total/accumulated/years', this.httpOptions);
    }

    getListAccumulated(searchForm: any) {
        let params = new HttpParams();
        if (searchForm.employee_name !== null) {
            params = params.set('employee_name', searchForm.employee_name);
        }
        if (searchForm.employee_name !== null) {
            params = params.set('employee_code', searchForm.employee_code);
        }
        if (searchForm.page !== null) {
            params = params.set('page', searchForm.page);
        }
        if (searchForm.year !== null) {
            params = params.set('year', searchForm.year);
        }

        if (searchForm.advanced_search) {
            if (searchForm.department !== null) {
                params = params.set('department_id', searchForm.department_id);
            }
            if (searchForm.position !== null) {
                params = params.set('position_id', searchForm.position_id);
            }
            if (searchForm.job_status !== null) {
                params = params.set('job_status_id', searchForm.job_status_id);
            }
            if (searchForm.working_status !== null) {
                params = params.set('working_status_id', searchForm.working_status_id);
            }
            if (searchForm.limit !== null) {
                params = params.set('limit', searchForm.limit);
            }
        }
        const httpOptions = {
            headers: this.headers,
            params: params
        };

        return this.http.get<any>('api/time-on/total/accumulated', httpOptions);
    }

    addAcculated(accumulated_id, data) {
        return this.http.post<any>('api/time-on/total/accumulated/' + accumulated_id + '/add', data, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    removeAcculatedAddition(addition_id) {
        return this.http.delete<any>('api/time-on/total/accumulated/additions/' + addition_id, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }

    addNote(time_on_month_id, data) {
        return this.http.post<any>('api/time-on/total/months/' + time_on_month_id + '/note', data, {
            headers: new HttpHeaders({
                'Authorization': 'Bearer ' + localStorage.getItem(TOKEN_NAME)
            })
        });
    }
}
