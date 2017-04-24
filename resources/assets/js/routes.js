import Dashboard from './views/Dashboard.vue'
import Parent from './views/Parent.vue'

export default [
    {
        path: '/dashboard',
        component: Dashboard,
        beforeEnter: requireAuth,
        children: [
            {
                path: '/',
                redirect: '/dashboard/home'
            },  
            {
                path: 'home',
                name: '控制面板',
                component: require('./views/Home.vue'),
            },
            {
                path: 'me',
                name: '个人中心',
                component: Parent,
                children: [
                    {
                        path: 'profile',
                        name: '我的资料',
                        component: require('./views/Profile.vue')
                    },
                    {
                        path: 'event',
                        name: '通知提醒',
                        component: require('./views/Event.vue')
                    }
                ]
            },
            {
                path: 'task',
                name: '计划任务',
                component: require('./views/Task.vue')
            },
            {
                path: 'units',
                name: '部门管理',
                component: require('./views/Unit.vue')
            },
            {
                path: 'weixin',
                name: '微信管理',
                component: require('./views/Weixin.vue')
            },
            {
                path: 'aged',
                name: '老年人口管理',
                component: require('./views/Weixin.vue')
            },
            {
                path: 'pops',
                component: Parent,
                children: [
                    {
                        path: '/',
                        name: '流动人口管理',
                        component: require('./views/pop/index.vue')
                    },
                    {
                        path: 'create',
                        name: '创建流动人口健康档案',
                        component: require('./views/pop/Create.vue')
                    },
                    {
                        path: ':id/edit',
                        name: '流动人口健康档案概览',
                        component: require('./views/pop/Edit.vue')
                    }
                ]
            },
            {
                path: 'users',
                component: Parent,
                children: [
                    {
                        path: '/',
                        name: '内部人员管理',
                        component: require('./views/user/Index.vue')
                    },
                    {
                        path: 'create',
                        name: '创建内部人员',
                        component: require('./views/user/Create.vue')
                    },
                    {
                        path: ':id/edit',
                        name: '编辑内部人员',
                        component: require('./views/user/Edit.vue')
                    }
                ]
            },
            // {
            //     path: 'site',
            //     name: '站点设置',
            //     component: require('./views/Weixin.vue')
            // },
            {
                path: '*',
                redirect: '/dashboard/home'
            }
        ]
    }
]

function requireAuth (to, from, next) {
    if (window.Wemesh.id) {
        return next()
    } else {
        return next('/login')
    }
}