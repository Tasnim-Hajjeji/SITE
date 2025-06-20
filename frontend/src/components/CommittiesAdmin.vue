<template>
    <div class="container">
      <h1 class="title">Committies</h1>
  
      <div class="actions">
        <button class="btn add" @click="openAddModal">
          <span class="plus">+</span> Add Commity Member
        </button>
  
        <!-- Edition Dropdown -->
        <div class="dropdown" @click="toggleEditionDropdown">
          <button class="btn edit">Edition ▼</button>
          <ul v-if="editionDropdownOpen" class="dropdown-menu">
            <li @click="onEditionOption('Edition 2025')">Edition 2025</li>
            <li @click="onEditionOption('Edition 2024')">Edition 2024</li>
            <li @click="onEditionOption('Edition 2023')">Edition 2023</li>
            <li @click="onEditionOption('Edition 2022')">Edition 2022</li>
          </ul>
        </div>
  
        <!-- Committee Type Dropdown -->
        <div class="dropdown" @click="toggleCommitteeDropdown">
          <button class="btn edit">Com ▼</button>
          <ul v-if="committeeDropdownOpen" class="dropdown-menu">
            <li @click="onCommitteeOption('All')">All</li>
            <li @click="onCommitteeOption('Honor')">Honor</li>
            <li @click="onCommitteeOption('Organizing')">Organizing</li>
            <li @click="onCommitteeOption('Scientific')">Scientific</li>
          </ul>
        </div>
      </div>
  
      <!-- Committee Members Table -->
      <div class="table-container">
        <div class="table-wrapper">
          <table class="members-table">
            <thead>
              <tr>
                <th>Full Name</th>
                <th>Role</th>
                <th>Etablissement</th>
                <th>Edition</th>
                <th>Commity Type</th>
                <th>Contact</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="member in filteredMembers" :key="member.id" class="member-row">
                <td class="name-cell">
                  <div
                    class="member-avatar"
                    :style="{ backgroundImage: `url(${member.avatar})` }"
                  ></div>
                  <span class="name">{{ member.fullName }}</span>
                </td>
                <td>
                  <span class="role">{{ member.role }}</span>
                </td>
                <td class="etablissement">{{ member.etablissement }}</td>
                <td class="edition">{{ member.edition }}</td>
                <td>
                  <span :class="['badge', member.committeeType.toLowerCase()]">
                    {{ member.committeeType }}
                  </span>
                </td>
                <td class="contact">
                  <div class="contact-wrapper">
                    <button
                      class="contact-btn phone"
                      @mouseenter="showTooltip($event, member.phone)"
                      @mouseleave="hideTooltip"
                      @click="contactMember(member, 'phone')"
                    >
                      <i class="fas fa-phone"></i>
                    </button>
                    <button
                      class="contact-btn email"
                      @mouseenter="showTooltip($event, member.email)"
                      @mouseleave="hideTooltip"
                      @click="contactMember(member, 'email')"
                    >
                      <i class="fas fa-envelope"></i>
                    </button>
                  </div>
                </td>
                <td class="actions-cell">
                  <button
                    class="action-btn edit-btn"
                    @click="openEditModal(member)"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                  <button
                    class="action-btn delete-btn"
                    @click="openDeleteModal(member)"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  
      <!-- Tooltip -->
      <div
        v-if="tooltip.show"
        class="tooltip"
        :style="{ top: tooltip.y + 'px', left: tooltip.x + 'px' }"
      >
        {{ tooltip.text }}
      </div>
  
      <!-- Toast Notification -->
      <div
        v-if="toast.show"
        class="toast-notification"
        :class="toast.type"
      >
        {{ toast.message }}
      </div>
  
      <!-- Modal Add Member -->
      <div v-if="showAddModal" class="modal-overlay" @click.self="closeAddModal">
        <div class="modal">
          <h3>Add Committee Member</h3>
          <form @submit.prevent="submitAddMember">
            <label>
              Full Name:
              <input type="text" v-model="newMember.fullName" required />
            </label>
            <label>
              Committee Type:
              <select v-model="newMember.committeeType" required>
                <option disabled value="">Select Committee</option>
                <option>Honor</option>
                <option>Organizing</option>
                <option>Scientific</option>
              </select>
            </label>
            <label>
              Role:
              <input type="text" v-model="newMember.role" required />
            </label>
            <label>
              Etablissement:
              <input type="text" v-model="newMember.etablissement" required />
            </label>
            <label>
              Image:
              <input type="file" accept="image/*" @change="onImageSelected($event, 'add')" />
            </label>
            <div v-if="newMember.avatar" class="preview-image">
              <img :src="newMember.avatar" alt="Preview Image" />
            </div>
            <label>
              Edition:
              <select v-model="newMember.edition" required>
                <option disabled value="">Select Edition</option>
                <option>SITE 2025</option>
                <option>SITE 2024</option>
                <option>SITE 2023</option>
                <option>SITE 2022</option>
              </select>
            </label>
            <label>
              Email:
              <input type="email" v-model="newMember.email" required />
            </label>
            <label>
              Phone:
              <input type="tel" v-model="newMember.phone" required />
            </label>
  
            <div class="modal-actions">
              <button type="submit" class="btn add">Add Member</button>
              <button type="button" class="btn" @click="closeAddModal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
  
      <!-- Modal Edit Member -->
      <div v-if="showEditModal" class="modal-overlay" @click.self="closeEditModal">
        <div class="modal">
          <h3>Edit Committee Member</h3>
          <form @submit.prevent="submitEditMember">
            <label>
              Full Name:
              <input type="text" v-model="editMemberData.fullName" required />
            </label>
            <label>
              Committee Type:
              <select v-model="editMemberData.committeeType" required>
                <option disabled value="">Select Committee</option>
                <option>Honor</option>
                <option>Organizing</option>
                <option>Scientific</option>
              </select>
            </label>
            <label>
              Role:
              <input type="text" v-model="editMemberData.role" required />
            </label>
            <label>
              Etablissement:
              <input type="text" v-model="editMemberData.etablissement" required />
            </label>
            <label>
              Image:
              <input type="file" accept="image/*" @change="onImageSelected($event, 'edit')" />
            </label>
            <div v-if="editMemberData.avatar" class="preview-image">
              <img :src="editMemberData.avatar" alt="Preview Image" />
            </div>
            <label>
              Edition:
              <select v-model="editMemberData.edition" required>
                <option disabled value="">Select Edition</option>
                <option>SITE 2025</option>
                <option>SITE 2024</option>
                <option>SITE 2023</option>
                <option>SITE 2022</option>
              </select>
            </label>
            <label>
              Email:
              <input type="email" v-model="editMemberData.email" required />
            </label>
            <label>
              Phone:
              <input type="tel" v-model="editMemberData.phone" required />
            </label>
  
            <div class="modal-actions">
              <button type="submit" class="btn edit">Save Changes</button>
              <button type="button" class="btn" @click="closeEditModal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
  
      <!-- Modal Delete Confirmation -->
      <div
        v-if="showDeleteModal"
        class="modal-overlay"
        @click.self="closeDeleteModal"
      >
        <div class="modal">
          <h3>Confirm Delete</h3>
          <p>Are you sure you want to delete member <strong>{{ deleteMemberData.fullName }}</strong>?</p>
          <div class="modal-actions">
            <button class="btn delete-btn" @click="confirmDeleteMember">Delete</button>
            <button class="btn" @click="closeDeleteModal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "CommittiesAdmin",
    data() {
      return {
        editionDropdownOpen: false,
        committeeDropdownOpen: false,
        selectedEdition: "All",
        selectedCommitteeType: "All",
        tooltip: {
          show: false,
          text: "",
          x: 0,
          y: 0,
        },
        toast: {
          show: false,
          message: "",
          type: "", // 'success' or 'error'
        },
        members: [
          {
            id: 1,
            fullName: "Samanta William",
            role: "enseignant",
            etablissement: "ensi",
            edition: "SITE 2025",
            committeeType: "Honor",
            avatar:
              "https://images.unsplash.com/photo-1494790108755-2616b612b988?w=150&h=150&fit=crop&crop=face&auto=format",
            phone: "+12 345 6789 0",
            email: "samanta@example.com",
          },
          {
            id: 2,
            fullName: "John Doe",
            role: "enseignant",
            etablissement: "ensi",
            edition: "SITE 2025",
            committeeType: "Organizing",
            avatar:
              "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face&auto=format",
            phone: "+12 345 6789 1",
            email: "john@example.com",
          },
          {
            id: 3,
            fullName: "Jane Smith",
            role: "enseignant",
            etablissement: "ensi",
            edition: "SITE 2025",
            committeeType: "Scientific",
            avatar:
              "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face&auto=format",
            phone: "+12 345 6789 2",
            email: "jane@example.com",
          },
          {
            id: 4,
            fullName: "Robert Johnson",
            role: "enseignant",
            etablissement: "ensi",
            edition: "SITE 2025",
            committeeType: "Honor",
            avatar:
              "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face&auto=format",
            phone: "+12 345 6789 3",
            email: "robert@example.com",
          },
          {
            id: 5,
            fullName: "Emily Davis",
            role: "enseignant",
            etablissement: "ensi",
            edition: "SITE 2025",
            committeeType: "Organizing",
            avatar:
              "https://images.unsplash.com/photo-1544723795-3fb6469f5b39?w=150&h=150&fit=crop&crop=face&auto=format",
            phone: "+12 345 6789 4",
            email: "emily@example.com",
          },
          {
            id: 6,
            fullName: "Michael Brown",
            role: "enseignant",
            etablissement: "ensi",
            edition: "SITE 2025",
            committeeType: "Honor",
            avatar:
              "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop&crop=face&auto=format",
            phone: "+12 345 6789 5",
            email: "michael@example.com",
          },
          {
            id: 7,
            fullName: "Sarah Wilson",
            role: "enseignant",
            etablissement: "ensi",
            edition: "SITE 2025",
            committeeType: "Scientific",
            avatar:
              "https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=150&h=150&fit=crop&crop=face&auto=format",
            phone: "+12 345 6789 6",
            email: "sarah@example.com",
          },
          {
            id: 8,
            fullName: "David Miller",
            role: "enseignant",
            etablissement: "ensi",
            edition: "SITE 2025",
            committeeType: "Honor",
            avatar:
              "https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?w=150&h=150&fit=crop&crop=face&auto=format",
            phone: "+12 345 6789 7",
            email: "david@example.com",
          },
        ],
        // Modals state
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        newMember: {
          fullName: "",
          committeeType: "",
          role: "",
          etablissement: "",
          avatar: "",
          edition: "",
          email: "",
          phone: "",
        },
        editMemberData: null,
        deleteMemberData: null,
        nextId: 9, // pour générer ID unique auto
      };
    },
    computed: {
      filteredMembers() {
        return this.members.filter((member) => {
          const editionMatch =
            this.selectedEdition === "All" ||
            member.edition.includes(this.selectedEdition.replace("Edition ", ""));
          const committeeMatch =
            this.selectedCommitteeType === "All" ||
            member.committeeType === this.selectedCommitteeType;
          return editionMatch && committeeMatch;
        });
      },
    },
    methods: {
      toggleEditionDropdown() {
        this.editionDropdownOpen = !this.editionDropdownOpen;
        this.committeeDropdownOpen = false;
      },
      toggleCommitteeDropdown() {
        this.committeeDropdownOpen = !this.committeeDropdownOpen;
        this.editionDropdownOpen = false;
      },
      onEditionOption(option) {
        this.selectedEdition = option;
        this.editionDropdownOpen = false;
      },
      onCommitteeOption(option) {
        this.selectedCommitteeType = option;
        this.committeeDropdownOpen = false;
      },
      showTooltip(event, text) {
        this.tooltip.show = true;
        this.tooltip.text = text;
        this.tooltip.x = event.clientX + 10;
        this.tooltip.y = event.clientY - 10;
      },
      hideTooltip() {
        this.tooltip.show = false;
      },
      async showToast(message, type = "success") {
        this.toast.message = message;
        this.toast.type = type;
        this.toast.show = true;
        setTimeout(() => {
          this.toast.show = false;
        }, 3000);
      },
      async contactMember(member, type) {
        try {
          let textToCopy = "";
          if (type === "phone") {
            textToCopy = member.phone;
          } else if (type === "email") {
            textToCopy = member.email;
          }
  
          await navigator.clipboard.writeText(textToCopy);
          this.showToast(
            `${type === "phone" ? "Phone number" : "Email"} copied to clipboard!`
          );
        } catch (err) {
          console.error("Failed to copy: ", err);
          this.showToast("Failed to copy to clipboard", "error");
        }
      },
      openAddModal() {
        // Reset form
        this.newMember = {
          fullName: "",
          committeeType: "",
          role: "",
          etablissement: "",
          avatar: "",
          edition: "",
          email: "",
          phone: "",
        };
        this.showAddModal = true;
      },
      closeAddModal() {
        this.showAddModal = false;
      },
      submitAddMember() {
        // Crée un nouveau membre avec un id auto généré
        const newMem = { ...this.newMember, id: this.nextId };
        this.nextId++;
        this.members.push(newMem);
        this.showToast("Member added successfully");
        this.closeAddModal();
      },
      openEditModal(member) {
        this.editMemberData = { ...member };
        this.showEditModal = true;
      },
      closeEditModal() {
        this.showEditModal = false;
        this.editMemberData = null;
      },
      submitEditMember() {
        // Trouver le membre dans le tableau original et mettre à jour ses données
        const index = this.members.findIndex(
          (m) => m.id === this.editMemberData.id
        );
        if (index !== -1) {
          this.members.splice(index, 1, { ...this.editMemberData });
          this.showToast("Member updated successfully");
        }
        this.closeEditModal();
      },
      openDeleteModal(member) {
        this.deleteMemberData = member;
        this.showDeleteModal = true;
      },
      closeDeleteModal() {
        this.showDeleteModal = false;
        this.deleteMemberData = null;
      },
      confirmDeleteMember() {
        const index = this.members.findIndex(
          (m) => m.id === this.deleteMemberData.id
        );
        if (index !== -1) {
          this.members.splice(index, 1);
          this.showToast("Member deleted successfully");
        }
        this.closeDeleteModal();
      },
      onImageSelected(event, mode) {
        const file = event.target.files[0];
        if (file && file.type.startsWith("image/")) {
          const imageUrl = URL.createObjectURL(file);
          if (mode === "add") {
            this.newMember.avatar = imageUrl;
          } else if (mode === "edit") {
            this.editMemberData.avatar = imageUrl;
          }
        } else {
          this.showToast("Please select a valid image file", "error");
        }
      },
    },
  };
  </script>
  
  
  <style scoped>
  /* Garde ton style précédent + ajouter styles pour modals */
  
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
  }
  
  .modal {
    background-color: white;
    border-radius: 16px;
    padding: 24px;
    width: 90%;
    max-width: 480px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  }
  
  .modal h3 {
    margin-bottom: 16px;
    color: #265985;
  }
  
  .modal label {
    display: block;
    margin-bottom: 12px;
    font-weight: 600;
    color: #374151;
  }
  
  .modal input,
  .modal select {
    width: 100%;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    margin-top: 4px;
    font-size: 14px;
    box-sizing: border-box;
  }
  
  .modal-actions {
    margin-top: 20px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
  }
  

@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

.container {
    padding: 24px;
    font-family: "Segoe UI", sans-serif;
    max-width: 100%;
    overflow-x: hidden;
}

.title {
    font-size: 32px;
    font-weight: bold;
    color: #265985;
    margin-bottom: 24px;
}

.actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}

.btn {
    padding: 12px 24px;
    border-radius: 9999px;
    font-weight: 500;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
}

.add {
    background-color: #265985;
    color: white;
}

.add:hover {
    background-color: #4338CA;
    transform: scale(1.05);
}

.edit {
    border: 1px solid #E5E7EB;
    color: #6B7280;
    background: white;
    min-width: 120px;
}

.edit:hover {
    border-color: #4F46E5;
    color: #4F46E5;
}

.dropdown {
    position: relative;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 5px;
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 100;
    list-style: none;
    padding: 8px 0;
    min-width: 160px;
    overflow: hidden;
}

.dropdown-menu li {
    padding: 10px 16px;
    cursor: pointer;
    transition: background 0.2s;
    font-size: 14px;
    color: #374151;
}

.dropdown-menu li:hover {
    background-color: #F3F4F6;
}

.table-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #F3F4F6;
    width: 100%;
}

.table-wrapper {
    overflow-x: auto;
    width: 100%;
}

.members-table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}

.members-table thead {
    background-color: #F9FAFB;
}

.members-table th {
    padding: 16px 20px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    border-bottom: 1px solid #E5E7EB;
    white-space: nowrap;
}

.member-row {
    transition: background-color 0.2s ease;
}

.member-row:hover {
    background-color: #F9FAFB;
}

.member-row td {
    vertical-align: middle;
}

.members-table th,
.members-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #E5E7EB;
    vertical-align: middle;
}

.name-cell {
    display: table-cell;
    /* ← restores default cell behavior */
    vertical-align: middle;
    /* padding is inherited from the generic td rule above */
}

.name-cell .member-avatar {
    margin-right: 12px;
    /* ← simple spacing inside the cell */
}

.member-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border: 2px solid #e5e7eb;
    flex-shrink: 0;
}

.name {
    font-weight: 500;
    color: #1F2937;
}

.role {
    color: #6366F1;
    font-weight: 500;
    font-size: 14px;
}

.etablissement,
.edition {
    color: #6B7280;
    font-size: 14px;
}

.badge {
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 500;
    text-transform: lowercase;
    white-space: nowrap;
}

.badge.honor {
    background-color: #FEF3C7;
    color: #D97706;
}

.badge.organizing {
    background-color: #DBEAFE;
    color: #2563EB;
}

.badge.scientific {
    background-color: #FEE2E2;
    color: #DC2626;
}

.contact {
    min-width: 100px;
}

.actions-cell {
    min-width: 100px;
    margin-top: 35px;
}

.contact .contact-wrapper,
.actions-cell {
    display: flex;
    gap: 8px;
    align-items: center;
    justify-content: center;
    /* or flex-start, if you like them flushed left */
}

.contact-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.contact-btn.phone {
    background-color: #EBF4FF;
    color: #3B82F6;
}

.contact-btn.phone:hover {
    background-color: #3B82F6;
    color: white;
}

.contact-btn.email {
    background-color: #F3E8FF;
    color: #8B5CF6;
}

.contact-btn.email:hover {
    background-color: #8B5CF6;
    color: white;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    margin-bottom: 25px;
}

.edit-btn {
    background-color: #F0F9FF;
    color: #0369A1;
}

.edit-btn:hover {
    background-color: #0369A1;
    color: white;
}

.delete-btn {
    background-color: #FEF2F2;
    color: #DC2626;
}

.delete-btn:hover {
    background-color: #DC2626;
    color: white;
}

.tooltip {
    position: fixed;
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 12px;
    pointer-events: none;
    z-index: 1000;
    white-space: nowrap;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.toast-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 12px 24px;
    border-radius: 8px;
    background-color: #4CAF50;
    color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    animation: fadeIn 0.3s ease-in-out;
}

.toast-notification.error {
    background-color: #F44336;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .container {
        padding: 20px;
    }

    .members-table {
        min-width: 800px;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 16px;
    }

    .actions {
        flex-direction: column;
        align-items: stretch;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .members-table {
        min-width: 700px;
    }

    .members-table th,
    .members-table td {
        padding: 12px 16px;
        font-size: 13px;
    }

    .member-avatar {
        width: 32px;
        height: 32px;
    }

    .name-cell {
        min-width: 150px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 12px;
    }

    .title {
        font-size: 24px;
    }

    .members-table th,
    .members-table td {
        padding: 8px 12px;
    }

    .members-table {
        min-width: 600px;
    }
}


  /* Modal styles */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background-color: #000000a0;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1200;
  }
  
  .modal {
    background: white;
    border-radius: 12px;
    max-width: 450px;
    width: 100%;
    padding: 22px 25px;
    box-shadow: 0 0 12px rgb(0 0 0 / 0.2);
    max-height: 95vh;
    overflow-y: auto;
  }
  
  .modal h3 {
    margin-top: 0;
    font-weight: 700;
    color: #003366;
    margin-bottom: 15px;
  }
  
  .modal form label {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
    font-weight: 600;
    color: #333;
    font-size: 0.95rem;
  }
  
  .modal form input[type="text"],
  .modal form input[type="email"],
  .modal form input[type="tel"],
  .modal form select,
  .modal form input[type="file"] {
    padding: 7px 12px;
    font-size: 1rem;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-top: 6px;
    font-weight: 400;
  }
  
  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
  }
  
  .preview-image img {
    max-width: 120px;
    max-height: 120px;
    margin-top: 8px;
    border-radius: 12px;
    object-fit: cover;
    border: 1px solid #ddd;
  }
</style>