```vue
<template>
  <div class="container">
    <h1 class="title">Committees {{ this.selectedEditionName }}</h1>

    <div class="actions">
      <button class="btn add" @click="openAddModal">
        <span class="plus">+</span> Add Committee Member
      </button>

      <!-- Edition Dropdown -->
      <div class="dropdown" @click="toggleEditionDropdown">
        <button class="btn edit">
          {{ selectedEditionName || 'Select Edition' }} ▼
        </button>
        <ul v-if="editionDropdownOpen" class="dropdown-menu">
          <li v-for="edition in editions" :key="edition.id" @click="onEditionOption(edition)">
            {{ edition.name }}
          </li>
        </ul>
      </div>

      <!-- Committee Type Dropdown -->
      <div class="dropdown" @click="toggleCommitteeDropdown">
        <button class="btn edit">
          {{ selectedCommitteeType || 'Committee' }} ▼
        </button>
        <ul v-if="committeeDropdownOpen" class="dropdown-menu">
          <li @click="onCommitteeOption('All')">All</li>
          <li @click="onCommitteeOption('Honor')">Honor</li>
          <li @click="onCommitteeOption('Organizing')">Organizing</li>
          <li @click="onCommitteeOption('Scientific')">Scientific</li>
        </ul>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="loading">
      <i class="fas fa-spinner fa-spin"></i> Loading...
    </div>

    <!-- Committee Members Table -->
    <div class="table-container" v-else>
      <div class="table-wrapper">
        <table class="members-table">
          <thead>
            <tr>
              <th>Full Name</th>
              <th>Role</th>
              <th>Etablissement</th>
              <th>Edition</th>
              <th>Committee Type</th>
              <th>Contact</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="member in filteredMembers" :key="member.id" class="member-row" :id="`committee-${member.id}`">
              <td class="name-cell">
                <div class="member-avatar"
                  :style="{ backgroundImage: member.image_url ? `url(${getImageUrl(member.image_url)})` : 'url(https://via.placeholder.com/150)' }">
                </div>
                <span class="name">{{ member.full_name }}</span>
              </td>
              <td>
                <span class="role">{{ member.role }}</span>
              </td>
              <td class="etablissement">{{ member.from_etablissement }}</td>
              <td class="edition">{{ member.edition?.name }}</td>
              <td>
                <span :class="['badge', member.committee.toLowerCase()]">
                  {{ member.committee }}
                </span>
              </td>
              <td class="contact">
                <div class="contact-wrapper">
                  <button class="contact-btn phone" @mouseenter="showTooltip($event, member.phone)"
                    @mouseleave="hideTooltip" @click="contactMember(member, 'phone')" v-if="member.phone">
                    <i class="fas fa-phone"></i>
                  </button>
                  <button class="contact-btn email" @mouseenter="showTooltip($event, member.email)"
                    @mouseleave="hideTooltip" @click="contactMember(member, 'email')" v-if="member.email">
                    <i class="fas fa-envelope"></i>
                  </button>
                </div>
              </td>
              <td class="actions-cell">
                <button class="action-btn edit-btn" @click="openEditModal(member)">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn delete-btn" @click="openDeleteModal(member)">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && filteredMembers.length === 0" class="empty-state">
      No committee members found for the selected criteria.
    </div>

    <!-- Tooltip -->
    <div v-if="tooltip.show" class="tooltip" :style="{ top: tooltip.y + 'px', left: tooltip.x + 'px' }">
      {{ tooltip.text }}
    </div>

    <!-- Toast Notification -->
    <div v-if="toast.show" class="toast-notification" :class="toast.type">
      {{ toast.message }}
    </div>

    <!-- Modal Add Member -->
    <transition name="fade">
      <div v-if="showAddModal" class="modal-overlay" @click.self="closeAddModal">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Add Committee Member</h3>
          <form @submit.prevent="submitAddMember" class="space-y-4">
            <label class="block text-sm font-medium text-gray-700">
              Full Name:
              <input type="text" v-model="newMember.full_name" required class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </label>
            <label class="block text-sm font-medium text-gray-700">
              Committee Type:
              <select v-model="newMember.committee" required @change="handleCommitteeTypeChange" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <option disabled value="">Select Committee</option>
                <option value="Honor">Honor</option>
                <option value="Organizing">Organizing</option>
                <option value="Scientific">Scientific</option>
              </select>
            </label>
            <label v-if="showRoleField" class="block text-sm font-medium text-gray-700">
              Role:
              <select v-model="newMember.role" required class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <option disabled value="">Select Role</option>
                <option value="chair">chair</option>
                <option value="vicechair">vicechair</option>
                <option value="member">member</option>
              </select>
            </label>
            <label class="block text-sm font-medium text-gray-700">
              Etablissement:
              <input type="text" v-model="newMember.from_etablissement" required class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </label>
            <label class="block text-sm font-medium text-gray-700">
              Image:
              <input type="file" accept="image/*" @change="onImageSelected($event, 'add')" required class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </label>
            <div v-if="newMember.image_url" class="preview-image">
              <img :src="newMember.image_url" alt="Preview Image" />
            </div>
            <label class="block text-sm font-medium text-gray-700">
              Edition:
              <select v-model="newMember.edition_id" required class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <option disabled value="">Select Edition</option>
                <option v-for="edition in editions" :key="edition.id" :value="edition.id">
                  {{ edition.name }}
                </option>
              </select>
            </label>
            <label class="block text-sm font-medium text-gray-700">
              Email:
              <input type="email" v-model="newMember.email" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </label>
            <label class="block text-sm font-medium text-gray-700">
              Phone:
              <input type="tel" v-model="newMember.phone" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </label>
            <div class="modal-actions flex justify-end gap-2 mt-6">
              <button type="button" class="cancel-btn" @click="closeAddModal">Cancel</button>
              <button type="submit" class="add-btn" :disabled="isSubmitting">
                {{ isSubmitting ? 'Adding...' : 'Add Member' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Modal Edit Member -->
    <transition name="fade">
      <div v-if="showEditModal" class="modal-overlay" @click.self="closeEditModal">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Edit Committee Member</h3>
          <form @submit.prevent="submitEditMember" class="space-y-4">
            <label class="block text-sm font-medium text-gray-700">
              Full Name:
              <input type="text" v-model="editMemberData.full_name" required class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </label>
            <label class="block text-sm font-medium text-gray-700">
              Committee Type:
              <select v-model="editMemberData.committee" required @change="handleEditCommitteeTypeChange" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <option disabled value="">Select Committee</option>
                <option value="Honor">Honor</option>
                <option value="Organizing">Organizing</option>
                <option value="Scientific">Scientific</option>
              </select>
            </label>
            <label v-if="showEditRoleField" class="block text-sm font-medium text-gray-700">
              Role:
              <select v-model="editMemberData.role" required class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <option disabled value="">Select Role</option>
                <option value="chair">chair</option>
                <option value="vicechair">vicechair</option>
                <option value="member">member</option>
              </select>
            </label>
            <label class="block text-sm font-medium text-gray-700">
              Etablissement:
              <input type="text" v-model="editMemberData.from_etablissement" required class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </label>
            <label class="block text-sm font-medium text-gray-700">
              Image:
              <input type="file" accept="image/*" @change="onImageSelected($event, 'edit')" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
              <small>Leave empty to keep current image</small>
            </label>
            <div v-if="editMemberData.image_url" class="preview-image">
              <img :src="getImageUrl(editMemberData.image_url)" alt="Preview Image" />
            </div>
            <label class="block text-sm font-medium text-gray-700">
              Edition:
              <select v-model="editMemberData.edition_id" required class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <option disabled value="">Select Edition</option>
                <option v-for="edition in editions" :key="edition.id" :value="edition.id">
                  {{ edition.name }}
                </option>
              </select>
            </label>
            <label class="block text-sm font-medium text-gray-700">
              Email:
              <input type="email" v-model="editMemberData.email" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </label>
            <label class="block text-sm font-medium text-gray-700">
              Phone:
              <input type="tel" v-model="editMemberData.phone" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
            </label>
            <div class="modal-actions flex justify-end gap-2 mt-6">
              <button type="button" class="cancel-btn" @click="closeEditModal">Cancel</button>
              <button type="submit" class="add-btn" :disabled="isSubmitting">
                {{ isSubmitting ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Modal Delete Confirmation -->
    <transition name="fade">
      <div v-if="showDeleteModal" class="modal-overlay" @click.self="closeDeleteModal">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Confirm Delete</h3>
          <p class="text-gray-600 mb-4 text-center">Are you sure you want to delete member <strong>{{ deleteMemberData.full_name }}</strong>?</p>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button" class="cancel-btn" @click="closeDeleteModal">Cancel</button>
            <button type="button" class="delete-btn" @click="confirmDeleteMember" :disabled="isSubmitting">
              {{ isSubmitting ? 'Deleting...' : 'Delete' }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import CommitteeMemberService from '@/services/CommitteeMemberService';
import EditionService from '@/services/EditionService';

export default {
  name: "CommitteesAdmin",
  data() {
    return {
      loading: true,
      editionDropdownOpen: false,
      committeeDropdownOpen: false,
      selectedEditionId: null,
      selectedEditionName: '',
      selectedCommitteeType: "All",
      editions: [],
      members: [],
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
      showAddModal: false,
      showEditModal: false,
      showDeleteModal: false,
      showRoleField: false,
      showEditRoleField: false,
      isSubmitting: false,
      newMember: {
        full_name: "",
        committee: "",
        role: "member",
        from_etablissement: "",
        edition_id: "",
        email: "",
        phone: "",
        image: null,
        image_url: "",
      },
      editMemberData: null,
      deleteMemberData: null,
    };
  },
  computed: {
    filteredMembers() {
      return this.members.filter((member) => {
        const editionMatch =
          this.selectedEditionId === null ||
          member.edition_id === this.selectedEditionId;
        const committeeMatch =
          this.selectedCommitteeType === "All" ||
          member.committee === this.selectedCommitteeType;
        return editionMatch && committeeMatch;
      });
    },
  },
  async created() {
    await this.fetchEditions();
    this.initializeSelectedEdition();
    await this.fetchMembers();
  },
  watch: {
    '$route.query': {
      immediate: true,
      handler(query) {
        if (query.highlightType === 'committee' && query.highlight) {
          this.highlightCommitteeMember(parseInt(query.highlight, 10));
        }
      }
    }
  },
  methods: {
    async fetchEditions() {
      try {
        const response = await EditionService.getAllEditions();
        this.editions = response.data;
      } catch (error) {
        console.error("Error fetching editions:", error);
        this.showToast("Failed to load editions", "error");
      }
    },
    getImageUrl(imagePath) {
      return `http://localhost:8000/storage/${imagePath}`;
    },
    initializeSelectedEdition() {
      const routeEditionId = this.$route.params.editionId;
      if (routeEditionId) {
        this.selectedEditionId = parseInt(routeEditionId);
        const edition = this.editions.find(e => e.id === this.selectedEditionId);
        if (edition) {
          this.selectedEditionName = edition.name;
        }
      } else {
        const storedEditionId = localStorage.getItem('selectedEditionId');
        if (storedEditionId) {
          this.selectedEditionId = parseInt(storedEditionId);
          const edition = this.editions.find(e => e.id === this.selectedEditionId);
          if (edition) {
            this.selectedEditionName = edition.name;
          }
        } else if (this.editions.length > 0) {
          this.selectedEditionId = this.editions[0].id;
          this.selectedEditionName = this.editions[0].name;
        }
      }
    },
    async fetchMembers() {
      this.loading = true;
      try {
        const response = await CommitteeMemberService.getAllCommitteeMembersByEdition(this.selectedEditionId);
        this.members = response.data;
      } catch (error) {
        console.error("Error fetching committee members:", error);
        this.showToast("Failed to load committee members", "error");
      } finally {
        this.loading = false;
      }
    },
    toggleEditionDropdown() {
      this.editionDropdownOpen = !this.editionDropdownOpen;
      this.committeeDropdownOpen = false;
    },
    toggleCommitteeDropdown() {
      this.committeeDropdownOpen = !this.committeeDropdownOpen;
      this.editionDropdownOpen = false;
    },
    onEditionOption(edition) {
      this.selectedEditionId = edition.id;
      this.selectedEditionName = edition.name;
      localStorage.setItem('selectedEditionId', edition.id);
      this.editionDropdownOpen = false;
    },
    onCommitteeOption(option) {
      this.selectedCommitteeType = option;
      this.committeeDropdownOpen = false;
    },
    handleCommitteeTypeChange() {
      this.showRoleField = this.newMember.committee !== 'Honor';
      if (!this.showRoleField) {
        this.newMember.role = 'member';
      }
    },
    handleEditCommitteeTypeChange() {
      this.showEditRoleField = this.editMemberData.committee !== 'Honor';
      if (!this.showEditRoleField) {
        this.editMemberData.role = 'member';
      }
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
      this.newMember = {
        full_name: "",
        committee: "",
        role: "member",
        from_etablissement: "",
        edition_id: this.selectedEditionId || (this.editions.length > 0 ? this.editions[0].id : ""),
        email: "",
        phone: "",
        image: null,
        image_url: "",
      };
      this.showRoleField = false;
      this.showAddModal = true;
    },
    closeAddModal() {
      this.showAddModal = false;
    },
    async submitAddMember() {
      this.isSubmitting = true;
      try {
        const formData = new FormData();
        formData.append('full_name', this.newMember.full_name);
        formData.append('committee', this.newMember.committee);
        formData.append('role', this.newMember.role);
        formData.append('from_etablissement', this.newMember.from_etablissement);
        formData.append('edition_id', this.newMember.edition_id);
        if (this.newMember.email) formData.append('email', this.newMember.email);
        if (this.newMember.phone) formData.append('phone', this.newMember.phone);
        if (this.newMember.image) {
          formData.append('image', this.newMember.image);
        } else {
          this.showToast("Please select an image", "error");
          this.isSubmitting = false;
          return;
        }
        const response = await CommitteeMemberService.createCommitteeMember(formData);
        this.members.push(response.data);
        this.showToast("Member added successfully");
        this.closeAddModal();
        this.fetchMembers();
      } catch (error) {
        console.error("Error adding member:", error);
        if (error.response && error.response.status === 422) {
          const errors = error.response.data.errors;
          const firstError = Object.values(errors)[0][0];
          this.showToast(firstError || "Validation error", "error");
        } else {
          this.showToast("Failed to add member", "error");
        }
      } finally {
        this.isSubmitting = false;
      }
    },
    openEditModal(member) {
      this.editMemberData = { ...member, role: member.role.toLowerCase() };
      this.showEditRoleField = this.editMemberData.committee !== 'Honor';
      this.showEditModal = true;
    },
    closeEditModal() {
      this.showEditModal = false;
      this.editMemberData = null;
    },
    async submitEditMember() {
      this.isSubmitting = true;
      try {
        const formData = new FormData();
        formData.append('full_name', this.editMemberData.full_name);
        formData.append('committee', this.editMemberData.committee);
        formData.append('role', this.editMemberData.role);
        formData.append('from_etablissement', this.editMemberData.from_etablissement);
        formData.append('edition_id', this.editMemberData.edition_id);
        if (this.editMemberData.email) formData.append('email', this.editMemberData.email);
        if (this.editMemberData.phone) formData.append('phone', this.editMemberData.phone);
        if (this.editMemberData.image) formData.append('image', this.editMemberData.image);
        const response = await CommitteeMemberService.updateCommitteeMember(this.editMemberData.id, formData);
        const index = this.members.findIndex(m => m.id === this.editMemberData.id);
        if (index !== -1) {
          this.members.splice(index, 1, response.data);
        }
        this.showToast("Member updated successfully");
        this.closeEditModal();
      } catch (error) {
        console.error("Error updating member:", error);
        this.showToast("Failed to update member", "error");
      } finally {
        this.isSubmitting = false;
      }
    },
    openDeleteModal(member) {
      this.deleteMemberData = member;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.deleteMemberData = null;
    },
    async confirmDeleteMember() {
      this.isSubmitting = true;
      try {
        await CommitteeMemberService.deleteCommitteeMember(this.deleteMemberData.id);
        const index = this.members.findIndex(m => m.id === this.deleteMemberData.id);
        if (index !== -1) {
          this.members.splice(index, 1);
        }
        this.showToast("Member deleted successfully");
        this.closeDeleteModal();
      } catch (error) {
        console.error("Error deleting member:", error);
        this.showToast("Failed to delete member", "error");
      } finally {
        this.isSubmitting = false;
      }
    },
    onImageSelected(event, mode) {
      const file = event.target.files[0];
      if (file && file.type.startsWith("image/")) {
        const imageUrl = URL.createObjectURL(file);
        if (mode === "add") {
          this.newMember.image = file;
          this.newMember.image_url = imageUrl;
        } else if (mode === "edit") {
          this.editMemberData.image = file;
          this.editMemberData.image_url = imageUrl;
        }
      } else {
        this.showToast("Please select a valid image file", "error");
      }
    },
    highlightCommitteeMember(memberId) {
      if (this.members.length > 0) {
        this.scrollToCommitteeMember(memberId);
        return;
      }
      const checkInterval = setInterval(() => {
        if (this.members.length > 0) {
          clearInterval(checkInterval);
          this.scrollToCommitteeMember(memberId);
        }
      }, 100);
    },
    scrollToCommitteeMember(memberId) {
      this.$nextTick(() => {
        const element = document.getElementById(`committee-${memberId}`);
        if (element) {
          element.scrollIntoView({ behavior: 'smooth', block: 'center' });
          element.classList.add('highlighted');
          setTimeout(() => {
            element.classList.remove('highlighted');
          }, 3000);
        }
      });
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

.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
  font-size: 16px;
  color: #265985;
  gap: 10px;
}

.empty-state {
  text-align: center;
  padding: 40px;
  color: #6B7280;
  font-size: 16px;
  background: white;
  border-radius: 16px;
  margin-top: 20px;
}

.fa-spin {
  animation: fa-spin 2s infinite linear;
}

@keyframes fa-spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(359deg);
  }
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
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.45);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 10px;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  animation: fadeInZoom 0.3s ease-out;
  max-height: 80vh;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
}

@keyframes fadeInZoom {
  0% {
    opacity: 0;
    transform: scale(0.85);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.modal-content h3 {
  font-size: 1.3rem;
  margin-bottom: 1rem;
  color: #1b2d56;
  text-align: center;
}

.modal-content input,
.modal-content select {
  width: 95%;
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  margin-bottom: 12px;
  font-size: 14px;
}

.modal-content input:focus,
.modal-content select:focus {
  border-color: #265985;
  outline: none;
}

.modal-content label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
  color: #1f2937;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}

.add-btn {
  background: linear-gradient(to right, #265985, #1e4b6b);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.add-btn:hover {
  background: linear-gradient(to right, #1e4b6b, #163a52);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.cancel-btn {
  background: linear-gradient(to right, #d1d5db, #b0b7c3);
  color: #1f2937;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.cancel-btn:hover {
  background: linear-gradient(to right, #b0b7c3, #9ca3af);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.delete-btn {
  background: linear-gradient(to right, #e53935, #c62828);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.delete-btn:hover {
  background: linear-gradient(to right, #c62828, #b71c1c);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.preview-image img {
  max-width: 120px;
  max-height: 120px;
  margin-top: 8px;
  border-radius: 6px;
  object-fit: cover;
  border: 1px solid #d1d5db;
}

.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: scale(0.85);
}

/* Responsive Design */
@media (max-width: 1024px) {
  .container {
    padding: 20px;
  }

  .members-table {
    min-width: 800px;
  }

  .modal-content {
    padding: 20px;
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

  .modal-content {
    padding: 20px;
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

  .modal-content {
    padding: 20px;
  }
}
</style>