import {Injectable} from '@angular/core';

import {Http, Response, Headers} from '@angular/http';
import 'rxjs/add/operator/toPromise';
import * as jwt_decode from 'jwt-decode';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Router} from '@angular/router';
import {AuthService} from './authSevice';
import {listPermissonRepo} from '../models/api/response/PermissionReponse';

declare var swal: any;
export const TOKEN_NAME: string = 'jwt_token';

@Injectable()
export class PermisionService {
    constructor(public http: HttpClient, public auth: AuthService) {
    }

    getAllPermisson(): Promise<listPermissonRepo> {
        return this.http.get('api/screen_category/all/screens')
            .toPromise()
            .then(response => (response as listPermissonRepo))
            .catch(this.handleError);
    }


    // getScreen(id): Promise<Screen> {

    //     const url = 'api/screen/' + id;
    //     return this.http.get(url)
    //         .toPromise()
    //         .then(res => (res as ScreenRepo).screen as Screen)
    //         .catch(this.handleError);

    // }
    // ()
    createPermisson(body: any): Promise<any> {
        return this.http.post('api/permissions', body)
            .toPromise()
            .then(response => response)
            .catch(this.handleError);
    }

    // updateScreen(body: any, id: Number): Promise<any> {
    //     return this.http.put('api/screen/' + id, body)
    //         .toPromise()
    //         .then(response => response)
    //         .catch(this.handleError);
    // }
    getlistScreenSelected(id: any) {
        return this.http.get<any>('api/roles/' + id + '/screens');
    }

    private handleError(error: any): Promise<any> {
        console.error('An error occurred', error);
        return Promise.reject(error.message || error);
    }

    // createScrCategory(categoryname: any): Promise<ScreenCategoryRepo> {
    //     return this.http.post('api/screen_category', { name: categoryname, display_name: categoryname, description: categoryname })
    //         .toPromise()
    //         .then(response => (response as ScreenCategoryRepo))
    //         .catch(this.handleError);
    // }
    deletePerOfRole(idRole: any, body: any) {
        let url = 'api/roles/' + idRole + '/detach/screens';
        return this.http.post<any>(url, body);
    }


    addPersForRole(body: { screen_ids: any[] }, id: number) {
        let url = 'api/roles/' + id + '/attach/screens';
        return this.http.post<any>(url, body);
    }
}