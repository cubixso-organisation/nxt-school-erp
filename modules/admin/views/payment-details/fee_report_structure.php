<?php

use app\modules\admin\models\Campus;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Structure Report - AG Grid</title>

    <!-- Bootstrap CSS for styling -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- AG Grid CSS -->
    <link href="https://unpkg.com/ag-grid-community/styles/ag-grid.css" rel="stylesheet">
    <link href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css" rel="stylesheet">

    <style>
        #myGrid {
            height: 70vh;
            width: 100%;
        }

        .ag-theme-alpine {
            border-radius: 5px;
            padding: 10px;
            background: #fff;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <h2 class="text-center mt-4">Fee Report According to Fee Structure</h2>

        <div class="grid-container">
            <!-- Export Buttons -->
            <button id="btnExportExcel" class="btn btn-primary mb-2">Export to Excel</button>
            <button id="btnExportPDF" class="btn btn-secondary mb-2">Export to PDF</button>

            <!-- AG Grid Container -->
            <div id="myGrid" class="ag-theme-alpine"></div>
        </div>
    </div>

    <!-- AG Grid JS -->
    <script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>
    <!-- AG Grid Enterprise JS -->
    <script src="https://unpkg.com/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>
    <!-- jsPDF and jsPDF autotable for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>

    <script>
        let currentPage = 1; // Start from the first page
        let currentLimit = 10; // Default number of rows per page

        // Function to dynamically create column definitions based on fee structures
        function createColumnDefs(feeStructures) {
            const baseColumns = [{
                    headerName: "Student Name",
                    field: "name",
                    sortable: true,
                    filter: true
                },
                {
                    headerName: "Class",
                    field: "class",
                    sortable: true,
                    filter: true
                },
                {
                    headerName: "Section",
                    field: "section",
                    sortable: true,
                    filter: true
                },
                {
                    headerName: "Father's Name",
                    field: "father_name",
                    sortable: true,
                    filter: true
                }
            ];

            // Add grouped fee structure columns dynamically
            const feeColumns = feeStructures.map(fee => ({
                headerName: fee,
                children: [{
                        headerName: "Paid",
                        field: `${fee}_paid`,
                        sortable: true,
                        filter: true,
                        valueFormatter: params => params.value ? params.value.toLocaleString() : "0.00"
                    },
                    {
                        headerName: "Pending",
                        field: `${fee}_pending`,
                        sortable: true,
                        filter: true,
                        valueFormatter: params => params.value ? params.value.toLocaleString() : "0.00"
                    }
                ]
            }));

            return [...baseColumns, ...feeColumns];
        }

        // Function to map API response data to row data
        function createRowData(students, feeStructures) {
            return students.map(student => {
                const feeData = feeStructures.reduce((acc, fee) => {
                    const feeInfo = student.fees[fee] || {
                        paid: 0,
                        pending: 0
                    };
                    acc[`${fee}_paid`] = feeInfo.paid;
                    acc[`${fee}_pending`] = feeInfo.pending;
                    return acc;
                }, {});

                return {
                    name: student.name,
                    class: student.class,
                    section: student.section,
                    father_name: student.father_name,
                    ...feeData
                };
            });
        }

        // Fetch data from the API with the current page and limit
        async function fetchFeeData(page, limit) {
            try {
                const response = await fetch('https://api.nxtschools.com/api/v1/admin/fee-details/fee-report', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        campus_id: <?= (new Campus())->getCampusId(); ?>, // Replace with actual campus_id if needed
                        page: page,
                        limit: limit
                    })
                });

                if (!response.ok) {
                    throw new Error(`Error fetching data: ${response.statusText}`);
                }

                const data = await response.json();
                return data.report;
            } catch (error) {
                console.error('Error fetching fee data:', error);
                return null;
            }
        }

        // Initialize AG Grid with server-side row model
        function initializeGrid(apiResponse) {
            const columnDefs = createColumnDefs(apiResponse.feeStructures);

            const gridOptions = {
                columnDefs: columnDefs,
                rowModelType: 'infinite', // Using infinite row model for server-side pagination
                cacheBlockSize: currentLimit, // Set the page size
                paginationPageSize: currentLimit, // Set AG Grid's internal pagination size
                pagination: true,
                datasource: getDataSource(), // Set datasource for dynamic pagination
                defaultColDef: {
                    resizable: true,
                    sortable: true,
                    filter: true
                }
            };

            // Initialize the grid
            const gridDiv = document.querySelector('#myGrid');
            agGrid.createGrid(gridDiv, gridOptions);

            // Add Excel export
            // Add Excel export
            document.getElementById('btnExportExcel').addEventListener('click', async function() {
                // Fetch all data from the server
                const allData = await fetchFeeData(1, 10000); // Adjust 10000 as needed for your max rows
                if (allData && allData.students) {
                    const rowData = createRowData(allData.students, allData.feeStructures);
                    const columnDefs = createColumnDefs(allData.feeStructures);

                    // Create a temporary hidden div for the export grid
                    const tempDiv = document.createElement('div');
                    tempDiv.style.height = '1px';
                    tempDiv.style.width = '1px';
                    tempDiv.style.position = 'fixed';
                    tempDiv.style.left = '-1000px';
                    document.body.appendChild(tempDiv);

                    // Create a temporary grid with client-side row model
                    const tempGridOptions = {
                        columnDefs: columnDefs,
                        rowData: rowData,
                        rowModelType: 'clientSide',
                        defaultColDef: {
                            resizable: true,
                            sortable: true,
                            filter: true
                        }
                    };
                    agGrid.createGrid(tempDiv, tempGridOptions);

                    // Wait for grid to initialize, then export
                    setTimeout(() => {
                        tempGridOptions.api.exportDataAsExcel();
                        tempGridOptions.api.destroy();
                        document.body.removeChild(tempDiv);
                    }, 100); // 100ms delay to ensure grid is ready
                } else {
                    alert('Failed to fetch all data for export.');
                }
            });

            // Add PDF export
            document.getElementById('btnExportPDF').addEventListener('click', async function() {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF();

                // Fetch all data from the server
                const allData = await fetchFeeData(1, 10000); // Adjust as needed
                if (allData && allData.students) {
                    const rowData = createRowData(allData.students, allData.feeStructures);

                    // Column definitions for PDF (customize headers as needed)
                    const columns = gridOptions.columnDefs.map(col => col.headerName);

                    doc.text('Fee Report', 20, 10);
                    doc.autoTable({
                        head: [columns],
                        body: rowData.map(row => Object.values(row)),
                        startY: 20
                    });
                    doc.save('fee_report.pdf');
                } else {
                    alert('Failed to fetch all data for export.');
                }
            });
        }

        // Create datasource for server-side pagination
        function getDataSource() {
            return {
                getRows: async (params) => {
                    const page = params.startRow / currentLimit + 1; // Calculate the current page
                    const apiResponse = await fetchFeeData(page, currentLimit);

                    if (apiResponse && apiResponse.students) {
                        const rowData = createRowData(apiResponse.students, apiResponse.feeStructures);
                        params.successCallback(rowData, apiResponse.totalRows); // Send data to grid
                    } else {
                        params.failCallback(); // Handle failure
                    }
                }
            };
        }

        // Fetch initial data and initialize grid
        document.addEventListener('DOMContentLoaded', async function() {
            const apiResponse = await fetchFeeData(currentPage, currentLimit);

            if (apiResponse) {
                initializeGrid(apiResponse); // Initialize grid with the initial data
            } else {
                console.error('Failed to load initial data');
            }
        });
    </script>

</body>

</html>