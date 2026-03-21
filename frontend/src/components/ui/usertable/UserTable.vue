<script setup>
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Badge } from '@/components/ui/badge'

const users = [
  { id: "1", name: "Tiago Santos", email: "tiago@example.com", role: "Admin", status: "Active" },
  { id: "2", name: "Ana Silva", email: "ana.s@work.com", role: "Editor", status: "Inactive" },
  { id: "3", name: "Lucas Pereira", email: "lucas@dev.io", role: "Viewer", status: "Active" },
  { id: "4", name: "Maria Ferro", email: "m.ferro@c.pt", role: "Admin", status: "Pending" },
]

const getStatusVariant = (status) => {
  if (status === 'Active') return 'default'
  if (status === 'Inactive') return 'destructive'
  return 'secondary'
}
</script>

<template>
  <div class="rounded-md border bg-white">
    <Table>
      <TableCaption>A list of recently active project contributors.</TableCaption>
      <TableHeader>
        <TableRow>
          <TableHead class="w-[200px]">Name</TableHead>
          <TableHead>Email</TableHead>
          <TableHead>Role</TableHead>
          <TableHead class="text-right">Status</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="user in users" :key="user.id">
          <TableCell class="font-medium">{{ user.name }}</TableCell>
          <TableCell>{{ user.email }}</TableCell>
          <TableCell>{{ user.role }}</TableCell>
          <TableCell class="text-right">
            <Badge :variant="getStatusVariant(user.status)">
              {{ user.status }}
            </Badge>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>
  </div>
</template>