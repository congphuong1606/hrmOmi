import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router, NavigationEnd } from '@angular/router';
import swal from 'sweetalert2';
import * as constants from '../../constants';
declare var $: any;

@Component({
    selector: 'category-list-cmp',
    moduleId: module.id,
    templateUrl: 'category-list.component.html'
})

export class CategoryListComponent implements OnInit {
    listOtherCategories: any[] = constants.LIST_OTHER_CATEGORY;
    ngOnInit() {

    }

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private authService: AuthService,
        private router: Router) {
        // this.mobHeight =  parseInt (window.screen.height * 0.7) + "px"; 
    }

    goToOtherCategories(item: any) {
        this.router.navigate(['../danh-muc/danh-sach-trong-danh-muc-khac/', item.path], {
            queryParams: {
                category_type: item.type,
                category_name: item.name,
            }
        });
    }


    toggle() {
        $('.slide').slideToggle();
    }

    deletePersonnel() {
        swal({
            title: 'Xác nhận',
            text: "Bạn có muốn xóa bản ghi này",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không'
        }).then((result) => {
            if (result.value) {
                swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        })
    }
}
