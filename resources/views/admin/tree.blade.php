@extends('layouts.admin')

@section('title')
    Affiliates
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="card">
                    <div class="card-block">
                        <div id="appTree" class="dt-responsive table-responsive">
                            <div class="mb-6">
                                <vue-ads-table-tree
                                        :columns="columns"
                                        :rows="rows"
                                        :filter="filterValue"
                                        @filter-change="filterChange"
                                >
                                    <template slot="role" slot-scope="props">
                                        <span v-if="props.row.role == 3">Super</span>
                                    </template>
                                </vue-ads-table-tree>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.2/vue.js"></script>
    <script src="https://unpkg.com/vue-ads-table-tree@latest/dist/vue-ads-table-tree.umd.js"></script>
    <script>
        const VueAdsTableTree = window["vue-ads-table-tree"].default;
        $(document).ready(function () {
            new Vue({
                el: "#appTree",
                components: {
                    VueAdsTableTree,
                },
                props: {
                    itemsPerPage: 100
                },
                data: {
                    page: 1,
                    props: {
                        itemsPerPage: 100
                    },
                    filterValue: '',
                    classes: {
                        table: {
                            'vue-ads-border': true,
                            'vue-ads-w-full': true,
                        },
                    },
                    columns: [
                        {
                            property: 'id',
                            title: 'ID',
                            direction: null,
                            filterable: true,
                        },
                        {
                            property: 'email',
                            title: 'E-mail',
                            direction: null,
                            filterable: true,
                        },
                        {
                            property: 'userCount',
                            title: 'Players',
                            direction: null,
                            filterable: false,
                        },
                        {
                            property: 'role',
                            title: 'Role',
                            direction: null,
                            filterable: false,
                        },
                        {
                            property: 'percent',
                            title: 'Percent',
                            direction: null,
                            filterable: false,
                        },
                        {
                            property: 'benefit',
                            title: 'Benefits',
                            direction: null,
                            filterable: false,
                        },
                    ],
                    rows: {!! json_encode($newTree) !!}
                },
                mounted() {
                    this.$root.$children[0].itemsPerPage = 100;
                },
                methods: {
                    sleep (ms) {
                        return new Promise(resolve => setTimeout(resolve, ms));
                    },
                    filterChange (filter) {
                        this.filterValue = filter;
                    },
                },
            });
        });
    </script>
@endsection



@section('preCss')
    <link rel="stylesheet" href="https://unpkg.com/vue-ads-table-tree@latest/dist/vue-ads-table-tree.css">
    <style>
        .vue-ads-text-sm {
            font-size: 14px;
            line-height: 1.5;
        }
    </style>
@endsection
