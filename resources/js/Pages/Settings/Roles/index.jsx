import BreadCrumb from '@/Components/BreadCrumb'
import Card from '@/Components/Card'
import LinkButton from '@/Components/LinkButton'
import Table from '@/Components/Table'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'
import { Head, useForm, usePage } from '@inertiajs/react'
import React, { useEffect, useState } from 'react'

export default function index({ roles }) {

    // Bulk Delete Form Data
    const { props } = usePage();
    const { data: BulkselectedIds, setData: setBulkSelectedIds, delete: BulkDelete, reset: resetBulkSelectedIds } = useForm({
        ids: [],
    });


    // Single Delete Form Data
    const { data: SingleSelectedId, setData: setSingleSelectedId, delete: SingleDelete, reset: resetSingleSelectedId } = useForm({
        id: null,
    })

    const [columns, setColumns] = useState([]);
    useEffect(() => {

        const columns = [
            { key: 'name', label: 'Role Name' },
            { key: 'guard_name', label: 'Guard Name' },
            { key: 'added_at', label: 'Created At' },
        ];


        setColumns(columns);

    }, []);


    return (
        <AuthenticatedLayout>

            <Head title='Roles' />

            <BreadCrumb
                header={"Roles"}
                parent={"Settings"}
                parent_link={route("settings.index")}
                child={"Roles"}
            />


            <Card
                Content={
                    <>
                        <div className="flex flex-wrap justify-end gap-5 my-3">
                            <LinkButton
                                Text={"Permissions"}
                                URL={route("settings.permissions.index")}
                                Icon={
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>


                                }
                            />


                            <LinkButton
                                Text={"Create Role"}
                                URL={route("settings.roles.create")}
                                Icon={
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>

                                }
                            />
                        </div>

                        <Table
                            setBulkSelectedIds={setBulkSelectedIds}
                            setSingleSelectedId={setSingleSelectedId}
                            SingleSelectedId={SingleSelectedId}
                            resetBulkSelectedIds={resetBulkSelectedIds}
                            resetSingleSelectedId={resetSingleSelectedId}
                            BulkDeleteMethod={BulkDelete}
                            SingleDeleteMethod={SingleDelete}
                            EditRoute={"settings.roles.edit"}
                            BulkDeleteRoute={"settings.roles.destroybyselection"}
                            SearchRoute={"settings.roles.index"}
                            SingleDeleteRoute={"settings.roles.destroy"}
                            customActions={
                                [
                                    {
                                        label: "Permissions",
                                        type: "link",
                                        href: (item) => route("settings.roles.permissions", item.id),
                                    }
                                ]
                            }
                            items={roles}
                            props={props}
                            columns={columns}
                        />
                    </>
                }
            />

        </AuthenticatedLayout>
    )
}
