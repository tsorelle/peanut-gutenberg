/// <reference path="../../../../pnut/core/ViewModelBase.ts" />
/// <reference path='../../../../typings/knockout/knockout.d.ts' />
/// <reference path='../../../../pnut/core/Peanut.d.ts' />

namespace PeanutPermissions {

    interface IAccessPathListItem {
        path: string;
        roleNames: string;
    }
    export class PathAuthorizationsViewModel extends Peanut.ViewModelBase {
        allRoles: string[] = [];
        // observables

        pathList = ko.observableArray<IAccessPathListItem>();
        form = {
            path: ko.observable<string>(),
            assigned: ko.observableArray<string>([]),
            available: ko.observableArray<string>([]),
            delete: ko.observable<boolean>(),
            errorMessage: ko.observable<string>(),
            isNew: ko.observable(false)
        }
        init(successFunction?: () => void) {
            Peanut.logger.write('PathAuthorizations Init');
            const me = this;
            const request = null; 
            me.application.hideServiceMessages();
            me.application.showWaiter('Loading Authorization paths...');
            me.services.executeService('peanut.peanut-permissions::GetAccessPathList',request,
                function(serviceResponse: Peanut.IServiceResponse) {
                    if (serviceResponse.Result == Peanut.serviceResultSuccess) {
                        const response = serviceResponse.Value;
                        me.allRoles = response.roles;
                        me.pathList(response.paths);
                    }
                    me.application.hideWaiter();
                    me.bindDefaultSection();
                    successFunction();
                }
            ).fail(function () {
                let trace = me.services.getErrorInformation();
                me.application.hideWaiter();
                me.bindDefaultSection();
                successFunction();
            });
        }
        clearForm = () => {
            this.form.path('');
            this.form.assigned([]);
            this.form.available(this.allRoles);
            this.form.errorMessage('');
            this.form.delete(false);
            this.form.isNew(true);
        }

        assignForm = (item: IAccessPathListItem) => {
            this.form.path(item.path);

            const assigned = item.roleNames ? item.roleNames.split(',') : [];
            const available = this.allRoles.filter(role => !assigned.includes(role));

            this.form.assigned(assigned);
            this.form.available(available);
            this.form.errorMessage('');
            this.form.delete(false);
            this.form.isNew(false);
        }

        onAddRole = (selected: any) => {
            let me = this;
            me.form.assigned.push(selected);
            me.form.available.remove(selected);
            me.form.available.sort((left:string,right:string) => {
                return left.localeCompare(right);
            })
            // me.form.changed(true);
        };

        onRemoveRole = (selected: any) => {
            let me = this;
            me.form.assigned.remove(selected);
            me.form.available.push(selected);
            me.form.available.sort((left:string,right:string) => {
                return left.localeCompare(right);
            });
            // me.form.changed(true);
        };
        
        updatePath = (item: IAccessPathListItem) => {
            this.assignForm(item);
            this.showModal('access-path-modal');
        }

        newPath = () => {
            this.clearForm();
            this.showModal('access-path-modal');
        }

        doUpdatePath = () => {
            const me = this;

            const request = {
                path: me.form.path(),
                roleNames: me.form.assigned().join(','),
                action: me.form.delete() ? 'delete' : 'update'
            }
            if (!request.path) {
                me.form.errorMessage('Path is required');
                return;
            }
            if ((!me.form.delete() && !request.roleNames)) {
                this.form.errorMessage('Role names are required');
                return;
            }

            me.application.hideServiceMessages();
            me.application.showWaiter('Updating access paths...');

            me.services.executeService('peanut.peanut-permissions::UpdateAccessPath',request,
                function(serviceResponse: Peanut.IServiceResponse) {
                    if (serviceResponse.Result == Peanut.serviceResultSuccess) {
                        me.application.hideWaiter();
                        me.pathList(serviceResponse.Value);
                        me.hideModal('access-path-modal');
                    }
                }
            ).fail(function () {
                let trace = me.services.getErrorInformation();
                me.hideModal('access-path-modal');
            }).always(function () {
                me.application.hideWaiter();
            });
        }
    }

}
 
