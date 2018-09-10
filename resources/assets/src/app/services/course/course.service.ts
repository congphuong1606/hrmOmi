import {Injectable} from '@angular/core';
import 'rxjs/add/operator/toPromise';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../authSevice';
import * as constants from '../../constants';


@Injectable()
export class CourseService {
    baseUrl = 'api/courses';

    constructor(public http: HttpClient, public auth: AuthService) {
    }

    getList(page: number, limit: number, finished: string, searchData: string) {
        return this.http.get<any>(this.baseUrl + '?page=' + page + '&&limit='
            + limit + '&&finished=' + finished + '&&search_data=' + searchData);
    }

    create(body: any) {
        return this.http.post<any>(this.baseUrl, body);
    }

    getListCourseForUser(page: number, limit: number, finished: string) {
        const userId = JSON.parse(localStorage.getItem(constants.USER_INFO)).id;
        return this.http.get<any>('api/users/courses/own' + '?page=' + page + '&&limit=' + limit + finished);
    }

    get(id: number) {
        const url = this.baseUrl + '/' + id;
        return this.http.get<any>(url);
    }

    delete(id: number) {
        const url = this.baseUrl + '/' + id;
        return this.http.delete<any>(url);
    }

    update(id: number, body: any) {
        const url = this.baseUrl + '/' + id;
        return this.http.put<any>(url, body);
    }

    searchUser(department_id: string, job_position_id: string, search_value: string, roleId: any) {
        const url = this.baseUrl + '/users/search?' + 'department_id=' + department_id
            + '&&job_position_id=' + job_position_id + '&&search_value=' + search_value + '&&role_id=' + roleId;
        return this.http.get<any>(url);
    }

    getQrCode(idCourse: number) {
        const url = this.baseUrl + '/' + idCourse + '/qr_code';
        return this.http.get<any>(url);
    }

    getCoreForUser(idCourse: number) {
        const url = this.baseUrl + '/' + idCourse + '/training ';
        return this.http.get<any>(url);
    }

    updatePresence(body: { user_id: number; details: any[] }) {
        const url = this.baseUrl + '/manual/roll_up';
        return this.http.post<any>(url, body);
    }

    updateScore(body: any) {
        const url = 'api/training/manual/mark_score';
        return this.http.post<any>(url, body);

    }

    uploadFileScore(body: { course_id: number; file: any }) {
        const url = 'api/score_files';
        const formData = new FormData();
        formData.append('file', body.file);
        formData.append('course_id', '' + body.course_id);
        return this.http.post<any>(url, formData);
    }

    applyImportFile(courseScoreExcelFileId: number) {
        const url = 'api/score_files/' + courseScoreExcelFileId + '/apply';
        return this.http.get<any>(url);

    }

    getDetailFileImport(curentIdFileImport: number) {
        const url = 'api/score_files/' + curentIdFileImport;
        return this.http.get<any>(url);
    }

    downloadPDFScore(idCourse: number) {
        const url = 'api/training/course/' + idCourse + '/report';
        return this.http.get<any>(url, {responseType: 'blob' as 'json'});
    }

    sendResult(idCourse: number) {
        const url = 'api/courses/' + idCourse + '/send_score';
        return this.http.get<any>(url,);
    }
}
