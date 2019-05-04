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
                                    <template slot="title">
                                        <h2 class="leading-loose font-bold uppercase">
                                            Belgium royal family
                                        </h2>
                                    </template>
                                    <template
                                            slot="firstName"
                                            slot-scope="props">
                                        <a
                                                :href="`https://www.google.com/search?q=${props.row.firstName}+${props.row.lastName}`"
                                                target="_blank">@{{ props.row.firstName }}</a>
                                    </template>
                                    <template slot="filter">
                                        <h3 class="inline pr-2">Filter:</h3>
                                        <input
                                                v-model="filterValue"
                                                class="appearance-none border py-2 px-3"
                                                type="text"
                                                placeholder="Filter..."
                                        >
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
                        info: {
                            'vue-ads-text-center': true,
                            'vue-ads-py-6': true,
                            'vue-ads-text-sm': true,
                        },
                        'all/': {
                            'hover:vue-ads-bg-grey-lighter': true,
                        },
                        'even/': {
                            'vue-ads-bg-grey-lightest': true,
                        },
                        'odd/': {
                            'vue-ads-bg-white': true,
                        },
                        '0/': {
                            'vue-ads-bg-grey-lightest': false,
                            'hover:vue-ads-bg-grey-lighter': false,
                        },
                        '0_-1/': {
                            'vue-ads-border-b': true,
                        },
                        '/0_-1': {
                            'vue-ads-border-r': true,
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
                            property: 'countChild',
                            title: 'I уровень',
                            direction: null,
                            filterable: false,
                        },
                        {
                            property: 'totalCountChild',
                            title: 'Всего партнеров',
                            direction: null,
                            filterable: false,
                        },
                        {
                            property: 'deep',
                            title: 'Глубина',
                            direction: null,
                            filterable: false,
                        },
                        {
                            property: 'created_at',
                            title: 'Дата рег',
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
