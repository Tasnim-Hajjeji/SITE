```vue
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
      <transition name="fade">
        <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300" @click.self="closeAddModal">
          <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Add Committee Member</h3>
            <form @submit.prevent="submitAddMember" class="space-y-4">
              <div>
                <label for="fullName" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input
                  v-model="newMember.fullName"
                  id="fullName"
                  type="text"
                  placeholder="Enter full name"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                />
              </div>
              <div>
                <label for="committeeType" class="block text-sm font-medium text-gray-700">Committee Type</label>
                <select
                  v-model="newMember.committeeType"
                  id="committeeType"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                >
                  <option value="" disabled>Select committee type</option>
                  <option value="Honor">Honor</option>
                  <option value="Organizing">Organizing</option>
                  <option value="Scientific">Scientific</option>
                </select>
              </div>
              <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <input
                  v-model="newMember.role"
                  id="role"
                  type="text"
                  placeholder="Enter role"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                />
              </div>
              <div>
                <label for="etablissement" class="block text-sm font-medium text-gray-700">Etablissement</label>
                <input
                  v-model="newMember.etablissement"
                  id="etablissement"
                  type="text"
                  placeholder="Enter etablissement"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                />
              </div>
              <div>
                <label for="avatar" class="block text-sm font-medium text-gray-700">Image</label>
                <input
                  type="file"
                  id="avatar"
                  accept="image/*"
                  @change="onImageSelected($event, 'add')"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                />
                <div v-if="newMember.avatar" class="preview-image mt-2">
                  <img :src="newMember.avatar" alt="Preview Image" class="max-w-[120px] max-h-[120px] rounded-lg border border-gray-300 object-cover" />
                </div>
              </div>
              <div>
                <label for="edition" class="block text-sm font-medium text-gray-700">Edition</label>
                <select
                  v-model="newMember.edition"
                  id="edition"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                >
                  <option value="" disabled>Select edition</option>
                  <option value="SITE 2025">SITE 2025</option>
                  <option value="SITE 2024">SITE 2024</option>
                  <option value="SITE 2023">SITE 2023</option>
                  <option value="SITE 2022">SITE 2022</option>
                </select>
              </div>
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                  v-model="newMember.email"
                  id="email"
                  type="email"
                  placeholder="Enter email"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                />
              </div>
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input
                  v-model="newMember.phone"
                  id="phone"
                  type="tel"
                  placeholder="Enter phone number"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                />
              </div>
              <div class="flex justify-end space-x-3 mt-6">
                <button type="button" class="btn cancel" @click="closeAddModal">Cancel</button>
                <button type="submit" class="btn add">Add Member</button>
              </div>
            </form>
          </div>
        </div>
      </transition>
  
      <!-- Modal Edit Member -->
      <transition name="fade">
        <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300" @click.self="closeEditModal">
          <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Edit Committee Member</h3>
            <form @submit.prevent="submitEditMember" class="space-y-4">
              <div>
                <label for="editFullName" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input
                  v-model="editMemberData.fullName"
                  id="editFullName"
                  type="text"
                  placeholder="Enter full name"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                />
              </div>
              <div>
                <label for="editCommitteeType" class="block text-sm font-medium text-gray-700">Committee Type</label>
                <select
                  v-model="editMemberData.committeeType"
                  id="editCommitteeType"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                >
                  <option value="" disabled>Select committee type</option>
                  <option value="Honor">Honor</option>
                  <option value="Organizing">Organizing</option>
                  <option value="Scientific">Scientific</option>
                </select>
              </div>
              <div>
                <label for="editRole" class="block text-sm font-medium text-gray-700">Role</label>
                <input
                  v-model="editMemberData.role"
                  id="editRole"
                  type="text"
                  placeholder="Enter role"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                />
              </div>
              <div>
                <label for="editEtablissement" class="block text-sm font-medium text-gray-700">Etablissement</label>
                <input
                  v-model="editMemberData.etablissement"
                  id="editEtablissement"
                  type="text"
                  placeholder="Enter etablissement"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                />
              </div>
              <div>
                <label for="editAvatar" class="block text-sm font-medium text-gray-700">Image</label>
                <input
                  type="file"
                  id="editAvatar"
                  accept="image/*"
                  @change="onImageSelected($event, 'edit')"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                />
                <div v-if="editMemberData.avatar" class="preview-image mt-2">
                  <img :src="editMemberData.avatar" alt="Preview Image" class="max-w-[120px] max-h-[120px] rounded-lg border border-gray-300 object-cover" />
                </div>
              </div>
              <div>
                <label for="editEdition" class="block text-sm font-medium text-gray-700">Edition</label>
                <select
                  v-model="editMemberData.edition"
                  id="editEdition"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                >
                  <option value="" disabled>Select edition</option>
                  <option value="SITE 2025">SITE 2025</option>
                  <option value="SITE 2024">SITE 2024</option>
                  <option value="SITE 2023">SITE 2023</option>
                  <option value="SITE 2022">SITE 2022</option>
                </select>
              </div>
              <div>
                <label for="editEmail" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                  v-model="editMemberData.email"
                  id="editEmail"
                  type="email"
                  placeholder="Enter email"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                />
              </div>
              <div>
                <label for="editPhone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input
                  v-model="editMemberData.phone"
                  id="editPhone"
                  type="tel"
                  placeholder="Enter phone number"
                  class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                  required
                />
              </div>
              <div class="flex justify-end space-x-3 mt-6">
                <button type="button" class="btn cancel" @click="closeEditModal">Cancel</button>
                <button type="submit" class="btn add">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </transition>
  
      <!-- Modal Delete Confirmation -->
      <transition name="fade">
        <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300" @click.self="closeDeleteModal">
          <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Delete Committee Member</h3>
            <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete <strong>{{ deleteMemberData.fullName }}</strong>?</p>
            <div class="flex justify-end space-x-3">
              <button type="button" class="btn cancel" @click="closeDeleteModal">Cancel</button>
              <button type="button" class="btn delete" @click="confirmDeleteMember">Yes, Delete</button>
            </div>
          </div>
        </div>
      </transition>
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
      nextId: 9,
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
        console.error("Failed to copy:", err);
        this.showToast("Failed to copy to clipboard", "error");
      }
    },
    openAddModal() {
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
    background-color: #1e476b;
    transform: scale(1.05);
}

.cancel {
    background: #999;
    color: white;
    border: none;
    padding: 0.3rem 1rem;
    border-radius: 5px;
}

.delete {
    background: #eb5a5a;
    color: white;
    border: none;
    padding: 0.3rem 1rem;
    border-radius: 5px;
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
    vertical-align: middle;
}

.name-cell .member-avatar {
    margin-right: 12px;
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

/* Modal styles */
.font-poppins {
    font-family: 'Poppins', sans-serif;
}

.fade-enter-active,
.fade-leave-active {
    transition: all 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-5px);
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
</style>
```