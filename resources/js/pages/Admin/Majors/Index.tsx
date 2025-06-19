import AppLayout from '@/layouts/app-layout'; // <-- DIUBAH DI SINI
import { Head, Link, usePage } from '@inertiajs/react';
import { PageProps, Major, PaginatedResponse } from '@/types';
import PrimaryButton from '@/Components/PrimaryButton';

export default function Index({ auth, majors }: PageProps<{ majors: PaginatedResponse<Major> }>) {
    const { flash } = usePage().props;

    return (
        <AppLayout // <-- DIUBAH DI SINI
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Manajemen Jurusan</h2>}
        >
            <Head title="Manajemen Jurusan" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <Link href={route('majors.create')}>
                                <PrimaryButton className="mb-4">
                                    Tambah Jurusan
                                </PrimaryButton>
                            </Link>

                            {flash.success && (
                                <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <span className="block sm:inline">{flash.success}</span>
                                </div>
                            )}

                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Singkatan</th>
                                        <th scope="col" className="relative px-6 py-3">
                                            <span className="sr-only">Aksi</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {majors.data.length === 0 ? (
                                        <tr>
                                            <td colSpan={3} className="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                                Tidak ada data jurusan.
                                            </td>
                                        </tr>
                                    ) : (
                                        majors.data.map((major) => (
                                            <tr key={major.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">{major.name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap">{major.short_name}</td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <Link href={route('majors.edit', major.id)} className="text-indigo-600 hover:text-indigo-900">
                                                        Edit
                                                    </Link>
                                                    <Link 
                                                        href={route('majors.destroy', major.id)} 
                                                        method="delete" 
                                                        as="button"
                                                        className="text-red-600 hover:text-red-900 ml-4"
                                                        onBefore={() => confirm('Anda yakin ingin menghapus jurusan ini?')}
                                                    >
                                                        Hapus
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout> // <-- DIUBAH DI SINI
    );
}