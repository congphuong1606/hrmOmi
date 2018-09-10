


import { Injectable } from '@angular/core';

import { Http, Response, Headers } from '@angular/http';
import 'rxjs/add/operator/toPromise';
import * as jwt_decode from 'jwt-decode';
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Router } from "@angular/router";
import { AuthService } from '../authSevice';
import { Screen, ListScreenRepo, ListScrCategory, ScreenCategoryRepo, ScreenRepo } from '../../models/api/category-response/ScreenReponse';
declare var swal: any;
export const TOKEN_NAME: string = 'jwt_token';

@Injectable()
export class OfficeService {
    constructor(public http: HttpClient, public auth: AuthService) {
    }
    getList(): Promise<any> {
        return this.http.get('api/positions')
            .toPromise()
            .then(response => response)
            .catch(this.handleError);
    }
    // TẠO MỚI ROLE
    create(body: any): Promise<any> {
        return this.http
            .post('api/positions', body)
            .toPromise()
            .then(res => res)
            .catch(this.handleError);
    }


    private handleError(error: any): Promise<any> {
        console.error('An error occurred', error);
        return Promise.reject(error.message || error);
    }

    get(id: number): Promise<any> {
        const url = 'api/positions/' + id;
        return this.http.get(url)
            .toPromise()
            .then(res => res)
            .catch(this.handleError);
    }
    delete(id: number): Promise<any> {
        let url = 'api/positions/' + id;
        return this.http.delete(url)
            .toPromise()
            .then(res => res)
            .catch(this.handleError);
    }
    update(id: number, body: any): Promise<any> {
        const url = 'api/positions/' + id;
        return this.http
            .put(url, body)
            .toPromise()
            .then(res => res)
            .catch(this.handleError);
    }
}