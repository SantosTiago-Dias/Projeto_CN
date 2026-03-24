<script setup>

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'

import { Button } from '@/components/ui/button'
import { Search, Bell, Plus, TrendingUp, Users, DollarSign } from 'lucide-vue-next'

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
          <Card class="shadow-sm border-none ring-1 ring-slate-200">

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