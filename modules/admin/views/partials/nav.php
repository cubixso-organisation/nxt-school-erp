<?php

use app\models\User;
use app\modules\admin\models\Campus;
use app\modules\admin\models\Institutes;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use app\modules\admin\widgets\Menu;
use yii\helpers\Url;


?>

<style>
	.sidebar {
		overflow: auto;
	}

	.sidebar::-webkit-scrollbar {
		width: 5px;
		/* width of the entire scrollbar */
	}

	.sidebar::-webkit-scrollbar-track {
		background: #24843a45;
		/* color of the tracking area */
	}

	.sidebar::-webkit-scrollbar-thumb {
		background-color: #24843a;
		/* color of the scroll thumb */
		border-radius: 20px;
		/* roundness of the scroll thumb */
		border: 3px solid #24843a;
		/* creates padding around scroll thumb */
	}
</style>

<div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			<ul id="myMenu">
				<li class="menu-title">
					<span></span>
				</li>








				<?php



				if (!empty(\Yii::$app->user->identity)) {
					if (\Yii::$app->user->identity->user_role == User::ROLE_ADMIN) { ?>
						<?= \Yii::$app->controller->renderPartial('@app/modules/admin/views/partials/admin_nav') ?>
					<?php } elseif (\Yii::$app->user->identity->user_role == User::ROLE_CAMPUS_ADMIN) {  ?>
						<?= \Yii::$app->controller->renderPartial('@app/modules/admin/views/partials/nav_campus_admin') ?>
					<?php } elseif (\Yii::$app->user->identity->user_role == User::ROLE_INSTITUTE_ADMIN) { ?>
						<?= \Yii::$app->controller->renderPartial('@app/modules/admin/views/partials/institute_admin') ?>

					<?php    } else if (\Yii::$app->user->identity->user_role == User::ROLE_LIBRARIAN) { ?>
						<?= \Yii::$app->controller->renderPartial('@app/modules/admin/views/partials/library_nav') ?>
					<?php    } else if (\Yii::$app->user->identity->user_role == User::ROLE_CHEF_WARDEN) { ?>
						<?= \Yii::$app->controller->renderPartial('@app/modules/admin/views/partials/chief_warden_nav') ?>
					<?php    } else { ?>
						<?= \Yii::$app->controller->renderPartial('@app/modules/admin/views/partials/campus_sub_admins') ?>




				<?php
					}
				}









				?>

			</ul>
		</div>
	</div>
</div>


<?php
// In your view file (e.g., admin panel header)
$filePath = Yii::getAlias('@app/modules/admin/views/partials/search.json');
$baseUrl = Yii::$app->request->baseUrl . "/admin";
$searchJson = '[ {
    "name": "Dashboard",
    "url": "dashboard"
  },
  {
    "name": "campus",
    "url":  "' . $baseUrl . '/campus"
  },
  {
    "name": "Users",
    "children": [
      {
        "name": "Users",
        "url": "' . $baseUrl . '/users"
      },
      {
        "name": "Key Person",
        "url": "' . $baseUrl . '/users/key-persons"
      },
      {
        "name": "Teacher Profile Edit",
        "url": "' . $baseUrl . '/index-teacher"
      },
      {
        "name": "Parent Profile Edit",
        "url": "' . $baseUrl . '/index-parent"
      }
    ]
  },
  {
    "name": "Notice Management",
    "children": [
      {
        "name": "Notice Boards",
        "url": "' . $baseUrl . '/notice-boards"
      },
      {
        "name": "Student Notice",
        "url": "' . $baseUrl . '/student-notice-boards"
      }
    ]
  },
  {
    "name": "Academics",
    "children": [
      {
        "name": "Subject Management",
        "children": [
          {
            "name": "Subjects",
            "url": "' . $baseUrl . '/subjects"
          },
          {
            "name": "Subjects Groups",
            "url": "' . $baseUrl . '/subject-groups"
          },
          {
            "name": "Subjects Time Table",
            "url": "' . $baseUrl . '/subject-timetable"
          }
        ]
      },
      {
        "name": "Teacher Management",
        "children": [
          {
            "name": "Teacher Details",
            "url": "' . $baseUrl . '/teacher-details"
          },
          {
            "name": "Assign Class Teacher",
            "url": "' . $baseUrl . '/class-teacher"
          },
          {
            "name": "Teacher Time Table",
            "url": "' . $baseUrl . '/subject-timetable/teacher-time-table"
          }
        ]
      },
      {
        "name": "Class Management",
        "children": [
          {
            "name": "class",
            "url": "' . $baseUrl . '/student-class"
          },
          {
            "name": "Section",
            "url": "' . $baseUrl . '/class-sections"
          },
          {
            "name": "Class Room",
            "url": "' . $baseUrl . '/class-rooms"
          },
          {
            "name": "Special COURSES",
            "url": "' . $baseUrl . '/special-courses"
          }
        ]
      },
      {
        "name": "Attendance Management",
        "children": [
          {
            "name": "Attendance Setting",
            "url": "' . $baseUrl . '/attendance-settings"
          },
          {
            "name": "Attendance Time Table",
            "url": "' . $baseUrl . '/attendance-time-tables"
          }
        ]
      },
      {
        "name": "Academic Year",
        "url": "' . $baseUrl . '/academic-years"
      },
      {
        "name": "Leave Type",
        "url": "' . $baseUrl . '/leave-types"
      },
      {
        "name": "Special Days",
        "url": "' . $baseUrl . '/special-days"
      },
      {
        "name": "Student Dairy",
        "url": "' . $baseUrl . '/student-dairy"
      }
    ]
  },
  {
    "name": "Student Management",
    "children": [
      {
        "name": "Parent Details",
        "url": "' . $baseUrl . '/parent-details"
      },
      {
        "name": "Student Details",
        "url": "' . $baseUrl . '/student-details"
      },
      {
        "name": "Student Form",
        "url": "' . $baseUrl . '/student-details/student-form-print"
      },
      {
        "name": "Promote Student",
        "url": "' . $baseUrl . '/student-details/promote-students"
      },
      {
        "name": "Student Attendance",
        "url": "' . $baseUrl . '/student-class-attendance"
      },
      {
        "name": "Teacher Notice",
        "url": "' . $baseUrl . '/notice-boards"
      }
    ]
  },

  {
    "name": "Bus Management",
    "children": [
      {
        "name": "Manage Bus Driver",
        "children": [
          {
            "name": "Create Bus Driver",
            "url": "' . $baseUrl . '/bus-details/driver-create"
          },
          {
            "name": "View Bus driver",
            "url": "' . $baseUrl . '/bus-details/bus-driver"
          },
          {
            "name": "Assign Bus Driver",
            "url": "' . $baseUrl . '/driver-has-bus/create"
          }
        ]
      },
      {
        "name": "Manage Bus",
        "children": [
          {
            "name": "Bus Create",
            "url": "' . $baseUrl . '/bus-details/create"
          },
          {
            "name": "Bus Details",
            "url": "' . $baseUrl . '/bus-details"
          },
          {
            "name": "Bus Route Create",
            "url": "' . $baseUrl . '/bus-route/create"
          },
          {
            "name": "View Bus Stop",
            "url": "' . $baseUrl . '/bus-route"
          },
          {
            "name": "Assign Student Bus",
            "url": "' . $baseUrl . '/student-has-bus"
          },
          {
            "name": "Bus Reports",
            "url": "' . $baseUrl . '/bus-details/bus-reports"
          }
        ]
      },
      {
        "name": "Manage Bus Coordinator",
        "children": [
          {
            "name": "Bus Coordinator Create",
            "url": "' . $baseUrl . '/bus-details/coordinator-create"
          },
          {
            "name": "Bus Coordinator",
            "url": "' . $baseUrl . '/bus-details/bus-coordinator"
          }
        ]
      }
    ]
  },
  {
    "name": "Fee Management",
    "children": [
      {
        "name": "Pay Fee",
        "url": "' . $baseUrl . '/pay-fees/assign-fee-details"
      },
      {
        "name": "Pay Old Fee",
        "url": "' . $baseUrl . '/pay-fees/pay-old-fee"
      },
      {
        "name": "Fees Types",
        "url": "' . $baseUrl . '/fees-typs/create"
      },
      {
        "name": "Fee Structure",
        "url": "' . $baseUrl . '/fee-structures"
      },
      {
        "name": "Bulk Fee Assign",
        "url": "' . $baseUrl . '/pay-fees"
      },
      {
        "name": "Fees Reports",
        "url": "' . $baseUrl . '/payment-details/fees-reports"
      },
      {
        "name": "Fees Balance Sheet",
        "url": "' . $baseUrl . '/fee-structures/balance-sheet"
      },
      {
        "name": "Transaction History",
        "url": "' . $baseUrl . '/payment-details"
      }
    ]
  },
  {
    "name": "Agent Management",
    "children": [
      {
        "name": "Create Agent",
        "url": "' . $baseUrl . '/student-details-agent-lead/agents-create"
      },
      {
        "name": "View Agents",
        "url": "' . $baseUrl . '/student-details-agent-lead/agents"
      },
      {
        "name": "Agents Admission",
        "url": "' . $baseUrl . '/student-details-agent-lead"
      },
      {
        "name": "Agent payment Details",
        "url": "' . $baseUrl . '/agent-student-join"
      }
    ]
  },
  {
    "name": "Inventory Management",
    "children": [
      {
        "name": "Item Supplier",
        "url": "' . $baseUrl . '/inventory/item-supplier-list"
      },
      {
        "name": "Item Store",
        "url": "' . $baseUrl . '/inventory/item-store"
      },
      {
        "name": "Item Category",
        "url": "' . $baseUrl . '/inventory/item-category"
      },
      {
        "name": "Inventory Item",
        "url": "' . $baseUrl . '/inventory/inventory-items"
      },
      {
        "name": "Add Item To Stock",
        "url": "' . $baseUrl . '/inventory/add-item-stock"
      },
      {
        "name": "Issue Item",
        "url": "' . $baseUrl . '/inventory/issue-return-inventory"
      }
    ]
  },
  {
    "name": "Document Generator",
    "children": [
      {
        "name": "Certificate Template",
        "url": "' . $baseUrl . '/document-generator/studentcertificates"
      },
      {
        "name": "Generate Certificate",
        "url": "' . $baseUrl . '/document-generator/studentcertificates/generate-certificate"
      },
      {
        "name": "Certificate List",
        "url": "' . $baseUrl . '/document-generator/studentcertificates/index-certificate-list"
      }
    ]
  },
  {
    "name": "Hostel Management",
    "children": [
      {
        "name": "Hostel",
        "url": "' . $baseUrl . '/hostel-management/hostels"
      },
      {
        "name": "Create Chief Warden",
        "url": "' . $baseUrl . '/hostel-management/hostels/create-chief-warden"
      },
      {
        "name": "Create Warden",
        "url": "' . $baseUrl . '/hostel-management/hostels/create-warden"
      },
      {
        "name": "Wardens List",
        "url": "' . $baseUrl . '/hostel-management/hostels/warden-list"
      },
      {
        "name": "Floor",
        "url": "' . $baseUrl . '/hostel-management/floor"
      },
      {
        "name": "Assign Warden To Hostel",
        "url": "' . $baseUrl . '/hostel-management/warden-to-hostel"
      },
      {
        "name": "Rooms",
        "url": "' . $baseUrl . '/hostel-management/rooms"
      },
      {
        "name": "Hostelers",
        "url": "' . $baseUrl . '/hostel-management/hostellers"
      },
      {
        "name": "Hostelers Attendance",
        "url": "' . $baseUrl . '/hostel-management/hostellers-attandance"
      },
      {
        "name": "Todays Hostelers Attendance",
        "url": "' . $baseUrl . '/hostel-management/hostellers-attandance/index-day-wise-attendance"
      },
      {
        "name": "Warden Attendance",
        "url": "' . $baseUrl . '/hostel-management/warden-attandance"
      },
      {
        "name": "Todays Warden Attendance",
        "url": "' . $baseUrl . '/hostel-management/warden-attandance/index-day-wise-attendance"
      }
    ]
  },
  {
    "name": "Library Management",
    "children": [
      {
        "name": "Available Books",
        "url": "' . $baseUrl . '/library-management/library-books"
      },
      {
        "name": "Create Librarian",
        "url": "' . $baseUrl . '/library-management/library-members/index-librarian"
      },
      {
        "name": "Issue Books",
        "url": "' . $baseUrl . '/library-management/issue-books"
      },
      {
        "name": "Books Racks",
        "url": "' . $baseUrl . '/library-management/library-racks"
      },
      {
        "name": "Members",
        "url": "' . $baseUrl . '/library-management/library-members"
      }
    ]
  },
  {
    "name": "Leave Management",
    "children": [
      {
        "name": "Create Leave Types",
        "url": "' . $baseUrl . '/leave-management/staff-leave-types/create"
      },
      {
        "name": "Leave Types",
        "url": "' . $baseUrl . '/leave-management/staff-leave-types"
      },
      {
        "name": "Leave Application",
        "url": "' . $baseUrl . '/leave-management/staff-leave-applied"
      }
    ]
  },
  {
    "name": "Child Assessment",
    "children": [
      {
        "name": "Child Merit",
        "url": "' . $baseUrl . '/child-assessment/child-merit"
      },
      {
        "name": "Assigned Merit",
        "url": "' . $baseUrl . '/child-assessment/merits-assigned-to-class"
      },
      {
        "name": "Student Merit Marks",
        "url": "' . $baseUrl . '/child-assessment/student-merit-marks"
      }
    ]
  },
  {
    "name": "Exam Management",
    "children": [
      {
        "name": "Update Teacher Details",
        "url": "' . $baseUrl . '/exam-management/teacher-class-and-subjects"
      },
      {
        "name": "Exams",
        "url": "' . $baseUrl . '/exams"
      },
      {
        "name": "Schedule Exam",
        "url": "' . $baseUrl . '/exam-management/exam-schedules"
      },
      {
        "name": "Exam Time Table",
        "url": "' . $baseUrl . '/exam-management/exam-schedules/create-time-table"
      },
      {
        "name": "Exam Result",
        "url": "' . $baseUrl . '/exams-result"
      },
      {
        "name": "Exam Wise Marksheet",
        "url": "' . $baseUrl . '/exam-management/exam-student-marksheet"
      },
      {
        "name": "Final Marksheet",
        "url": "' . $baseUrl . '/exam-management/final-marksheet"
      },
      {
        "name": "Exam Hall Ticket",
        "url": "' . $baseUrl . '/exam-management/exam-schedules/exam-hall-ticket"
      }
    ]
  },
  {
    "name": "Staff Management",
    "children": [
      {
        "name": "Designations",
        "url": "' . $baseUrl . '/staff-management/staff-designations"
      },
      {
        "name": "Staffs",
        "url": "' . $baseUrl . '/staff-management/staff-details"
      },
      {
        "name": "Staff Attendance Settings",
        "url": "' . $baseUrl . '/staff-management/staff-attendence-settings"
      },
      {
        "name": "Today Attendance",
        "url": "' . $baseUrl . '/staff-management/staff-attendence/today-attandance"
      },
      {
        "name": "Attendance History",
        "url": "' . $baseUrl . '/staff-management/staff-attendence"
      },
      {
        "name": "Salary",
        "children": [
          {
            "name": "Staff Salaries",
            "url": "' . $baseUrl . '/staff-management/staff-salary"
          },
          {
            "name": "Salary Groups",
            "url": "' . $baseUrl . '/staff-management/salary-groups"
          }
        ]
      },
      {
        "name": "Payroll Settings",
        "children": [
          {
            "name": "Payroll Components",
            "url": "' . $baseUrl . '/staff-management/salary-components"
          },
          {
            "name": "Payroll Group",
            "url": "' . $baseUrl . '/staff-management/salary-components"
          }
        ]
      }
    ]
  }
	
]';
// Check if the file exists
if (file_exists($filePath)) {
	$fileContent = file_get_contents($filePath);
	$jsonData = json_decode($searchJson, true);
} else {
	// Handle case when file does not exist
	throw new \yii\web\NotFoundHttpException('The requested file does not exist.');
}

// Encode JSON data for JavaScript usage
$searchDataJson = json_encode($jsonData);

?>
<script>
	function handleInput(searchValue) {
		searchValue = searchValue.toLowerCase();
		var searchData = <?= $searchDataJson ?>;
		var results = searchData.filter(function(item) {
			if (item.name.toLowerCase().includes(searchValue)) {
				return true; // Parent name matches, show both parent and children
			} else if (item.children) {
				var childMatches = item.children.some(function(child) {
					return child.name.toLowerCase().includes(searchValue);
				});
				return childMatches; // Show parent if any child matches
			} else {
				return false; // No match found
			}
		});
		updateDropdown(results);
	}

	function updateDropdown(results) {
		var dropdown = document.getElementById('searchDropdown');
		dropdown.innerHTML = ''; // Clear previous results
		results.forEach(function(result) {
			var html = buildDropdownItem(result);
			dropdown.insertAdjacentHTML('beforeend', html);
		});
		dropdown.style.display = results.length > 0 ? 'block' : 'none';
	}

	function buildDropdownItem(item) {
		var html = '<div class="dropdown-item">';
		if (item.url) {
			html += '<a href="' + item.url + '">' + item.name + '</a>';
		} else {
			html += item.name;
		}
		html += '</div>';
		if (item.children) {
			html += '<div class="dropdown-children">';
			item.children.forEach(function(child) {
				html += buildDropdownItem(child);
			});
			html += '</div>';
		}
		return html;
	}
</script>