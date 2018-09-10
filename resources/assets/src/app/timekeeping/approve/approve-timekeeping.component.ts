import { Component, OnInit } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../services/authSevice";
import { Router, NavigationEnd } from '@angular/router';

declare var $: any;
declare var swal: any;

@Component({
    selector: 'approve-timekeeping-cmp',
    moduleId: module.id,
    templateUrl: 'approve-timekeeping.component.html'
})

export class ApproveTimekeepingComponent implements OnInit {
    form: FormGroup

    ngOnInit() {

    }

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private authService: AuthService,
        private router: Router) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
    }

    goToPersonnelCreate() {
        this.router.navigate(['/danh-sach-nhan-su/them-nhan-su']);
    }

    toggle() {
        $('.slide').slideToggle();
    }

    deletePersonnel() {
        swal({
            title: 'Xác nhận',
            text: "Bạn có muốn xóa bản ghi này",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không'
        }).then((result) => {
            console.log(result)
            if (result) {
                swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        }).catch(swal.noop);
    }

}
