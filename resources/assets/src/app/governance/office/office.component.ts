import { Component, OnInit, AfterViewInit, OnDestroy } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router } from '@angular/router';
import { Office } from "../../models/api/category-response/officePositionReponse";
import { OfficeService } from "../../services/category/office.service";
import { DataGlobalService } from "../../services/data.global.service";
import { Subscription } from "rxjs/Subscription";
import { CategoryReponse } from "../../models/api/response/CategoriesReponse";
import { CategoryService } from "../../services/category/category.service";

declare var $: any;
declare var swal: any

@Component({
    selector: 'office-cmp',
    moduleId: module.id,
    templateUrl: 'office.component.html'
})

export class OfficeComponent implements OnInit, OnDestroy {
    dataRows: any[];
    subscriptionDataRows: Subscription;
    form: FormGroup;
    check = false;
    subscriptionRemove: Subscription;
    isAdmin = false;
    allowUpdate = false;
    allowAdd = false;

    setupData(): void {
        this.isAdmin = this.dataGlobalService.isAdmin();
        this.allowUpdate = this.checkPemision('/danh-muc/cap-nhat-chuc-danh');
        this.allowAdd = this.checkPemision('/danh-muc/them-chuc-danh');

    }

    ngOnDestroy(): void {
        this.subscriptionDataRows !== undefined ? this.subscriptionDataRows.unsubscribe() : console.log(':D');
        this.subscriptionRemove !== undefined ? this.subscriptionRemove.unsubscribe() : console.log(':D');
    }

    constructor(private router: Router,
        private service: CategoryService,
        private dataGlobalService: DataGlobalService) { }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-muc/chuc-danh')) {
            window.history.back();
        } else {
            this.setupData();
            this.getList();
        }

    }

    onBack(): void {
        this.ngOnDestroy();
        window.history.back();
    }

    getList(): void {
        this.subscriptionDataRows = this.service.getList('positions').subscribe(
            repo => {
                this.check = true;
                this.dataRows = (repo.positions as any[]);
            },
            error => {
                this.dataGlobalService.actionFail(error.error);
            });
    }

    updateListOffice(data: any): void {
        this.router.navigate(['../danh-muc/cap-nhat-chuc-danh'], { queryParams: data });
    }

    addOfficeAction(): void {
        this.router.navigate(['../danh-muc/them-chuc-danh']);
    }

    deleteOffice(data: any): void {
        swal({
            title: 'Xác nhận xóa?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 1000);
                });
            },
            allowOutsideClick: false
        }).then(result => {
            this.subscriptionRemove = this.service.delete('positions', data.id).subscribe(
                repo => {
                    if (repo.status === 'success') {
                        this.dataRows = this.dataRows.filter(h => h !== data);
                        swal(
                            'Đã Xóa!',
                            'Bạn đã xóa chức danh thành công ',
                            'success'
                        );
                    }
                },
                error => {
                    this.dataGlobalService.actionFail(error.error);
                });

        });

    }


    isLoadSuccess(): boolean {
        if (this.check) {
            $('[rel="tooltip"]').tooltip();
        }

        return this.check;
    }

    checkPemision(path: string): boolean {
        return this.dataGlobalService.checkPemisson(path);
    }

}