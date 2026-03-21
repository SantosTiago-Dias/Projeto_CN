<script setup>
import { ref } from 'vue'
import AppSidebar from '@/components/ui/appssidebar/Appsidebar.vue'
import UserTable from '@/components/ui/usertable/UserTable.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Search, Bell, Plus, TrendingUp, Users, DollarSign } from 'lucide-vue-next'

// Mock Stats Data
const stats = [
  {
    title: "Total Revenue",
    value: "$45,231",
    description: "+20% from last month",
    icon: DollarSign,
    color: "text-green-600"
  },
  {
    title: "Active Users",
    value: "2,350",
    description: "+180 new today",
    icon: Users,
    color: "text-blue-600"
  },
  {
    title: "Growth Rate",
    value: "12.5%",
    description: "+4% since yesterday",
    icon: TrendingUp,
    color: "text-purple-600"
  },
]
</script>

<template>
  <div class="flex min-h-screen bg-slate-50/50">
    <main class="flex-1 flex flex-col">
      <div class="p-8 space-y-8">

        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold tracking-tight">Dashboard Overview</h1>
            <p class="text-muted-foreground font-medium">Welcome back, Tiago. Here's what's happening today.</p>
          </div>
          <Button class="gap-2">
            <Plus class="h-4 w-4" /> New Report
          </Button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <Card v-for="stat in stats" :key="stat.title" class="shadow-sm border-none ring-1 ring-slate-200">
            <CardHeader class="flex flex-row items-center justify-between pb-2">
              <CardTitle class="text-sm font-medium text-muted-foreground">
                {{ stat.title }}
              </CardTitle>
              <component :is="stat.icon" :class="['h-4 w-4', stat.color]" />
            </CardHeader>
            <CardContent>
              <div class="text-2xl font-bold">{{ stat.value }}</div>
              <p class="text-xs text-muted-foreground mt-1 italic">
                {{ stat.description }}
              </p>
            </CardContent>
          </Card>
        </div>

        <Card class="border-none shadow-sm ring-1 ring-slate-200">
          <CardHeader>
            <div class="flex items-center justify-between">
              <div>
                <CardTitle>Project Members</CardTitle>
                <p class="text-sm text-muted-foreground">Manage your team and their access levels.</p>
              </div>
              <Button variant="ghost" size="sm" class="text-primary">View all</Button>
            </div>
          </CardHeader>
          <CardContent>
            <UserTable />
          </CardContent>
        </Card>

      </div>
    </main>
  </div>
</template>