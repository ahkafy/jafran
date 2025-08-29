@extends('layouts.app')

@section('title', 'Genealogy Tree')

@section('styles')
<style>
    .genealogy-wrapper {
        margin-bottom: 48px;
        min-height: 85vh;
        padding: 0px;
        border-radius: 12px;
    }

    .tree-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }

    .tree-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 30px;
        min-height: 60vh;
        overflow: auto;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }

    .tree-view {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .tree-node {
        margin: 10px 0;
        position: relative;
    }

    .node-content {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: relative;
        min-width: 280px;
        max-width: 350px;
    }

    .node-content:hover {
        border-color: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(79, 70, 229, 0.2);
    }

    .node-content.root {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        border-color: #4f46e5;
        font-weight: bold;
        box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
    }

    .node-content.level-1 {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-color: #10b981;
    }

    .node-content.level-2 {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border-color: #f59e0b;
    }

    .node-content.level-3 {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border-color: #ef4444;
    }

    .node-content.level-4 {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        border-color: #8b5cf6;
    }

    .expand-btn {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .expand-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }

    .expand-btn.expanded {
        transform: rotate(90deg);
    }

    .node-info {
        flex: 1;
    }

    .node-name {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .node-details {
        font-size: 11px;
        opacity: 0.9;
        display: flex;
        gap: 12px;
    }

    .node-badge {
        background: rgba(255,255,255,0.2);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
    }

    .children-container {
        margin-left: 30px;
        border-left: 2px dashed #cbd5e0;
        padding-left: 20px;
        display: none;
        animation: slideDown 0.3s ease;
    }

    .children-container.expanded {
        display: block;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .loading-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 200px;
        flex-direction: column;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #e2e8f0;
        border-left: 4px solid #4f46e5;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .stats-row {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }

    .stat-card {
        background: rgba(255,255,255,0.9);
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        flex: 1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #4f46e5;
    }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    .controls {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-tree {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-tree:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="genealogy-wrapper">
        <!-- Modern Header -->
        <div class="tree-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-2">
                        <i class="fas fa-sitemap text-primary"></i>
                        Team Network Tree
                    </h4>
                    <p class="text-muted mb-0">Modern expandable team structure</p>
                </div>
                <div class="col-md-6">
                    <div class="controls justify-content-end d-flex">
                        <button id="expandAll" class="btn-tree">
                            <i class="fas fa-expand-arrows-alt"></i> Expand All
                        </button>
                        <button id="collapseAll" class="btn-tree">
                            <i class="fas fa-compress-arrows-alt"></i> Collapse All
                        </button>
                        <a href="{{ route('network.genealogy') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list"></i> List View
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="stats-row" id="statsRow" style="display: none;">
                <div class="stat-card">
                    <div class="stat-number" id="totalMembers">0</div>
                    <div class="stat-label">Total Members</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="directReferrals">0</div>
                    <div class="stat-label">Direct Referrals</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="maxDepth">0</div>
                    <div class="stat-label">Max Depth</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="totalInvestment">$0</div>
                    <div class="stat-label">Total Investment</div>
                </div>
            </div>
        </div>

        <!-- Tree Container -->
        <div class="tree-container">
            <div id="loadingSpinner" class="loading-spinner">
                <div class="spinner"></div>
                <p class="mt-3 text-muted">Loading team network...</p>
            </div>

            <div id="treeView" class="tree-view" style="display: none;">
                <!-- Tree will be generated here -->
            </div>

            <div id="errorView" style="display: none; text-align: center; padding: 40px;">
                <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                <h5>Failed to load team network</h5>
                <p class="text-muted">Please try refreshing the page or contact support.</p>
                <button class="btn-tree" onclick="location.reload()">
                    <i class="fas fa-redo"></i> Retry
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
class ModernGenealogyTree {
    constructor() {
        this.treeData = null;
        this.stats = {
            totalMembers: 0,
            directReferrals: 0,
            maxDepth: 0,
            totalInvestment: 0
        };
        this.init();
    }

    async init() {
        try {
            await this.loadTreeData();
            this.renderTree();
            this.setupEventListeners();
            this.updateStats();
            this.showTree();
        } catch (error) {
            console.error('Error initializing tree:', error);
            this.showError();
        }
    }

    async loadTreeData() {
        const response = await fetch('{{ route("network.genealogy.data") }}?depth=5', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        this.treeData = await response.json();
    }

    renderTree() {
        const treeView = document.getElementById('treeView');
        treeView.innerHTML = '';

        if (this.treeData) {
            const rootNode = this.createTreeNode(this.treeData, 0, true);
            treeView.appendChild(rootNode);
        }
    }

    createTreeNode(nodeData, level = 0, isRoot = false) {
        const treeNode = document.createElement('div');
        treeNode.className = 'tree-node';
        treeNode.dataset.userId = nodeData.id;
        treeNode.dataset.level = level;

        const hasChildren = nodeData.children && nodeData.children.length > 0;

        // Create node content
        const nodeContent = document.createElement('div');
        nodeContent.className = `node-content ${isRoot ? 'root' : `level-${Math.min(level, 4)}`}`;

        // Expand button
        if (hasChildren) {
            const expandBtn = document.createElement('div');
            expandBtn.className = 'expand-btn';
            expandBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            expandBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleNode(treeNode);
            });
            nodeContent.appendChild(expandBtn);
        } else {
            const spacer = document.createElement('div');
            spacer.style.width = '24px';
            spacer.style.marginRight = '12px';
            nodeContent.appendChild(spacer);
        }

        // Node info
        const nodeInfo = document.createElement('div');
        nodeInfo.className = 'node-info';

        const nodeName = document.createElement('div');
        nodeName.className = 'node-name';
        nodeName.textContent = nodeData.name || nodeData.label || 'Unknown User';

        const nodeDetails = document.createElement('div');
        nodeDetails.className = 'node-details';

        const rankBadge = document.createElement('span');
        rankBadge.className = 'node-badge';
        rankBadge.textContent = nodeData.rank || 'Guest';

        const investmentBadge = document.createElement('span');
        investmentBadge.className = 'node-badge';
        investmentBadge.textContent = `$${nodeData.investment || 0}`;

        const referralsBadge = document.createElement('span');
        referralsBadge.className = 'node-badge';
        referralsBadge.textContent = `${nodeData.referrals_count || 0} refs`;

        nodeDetails.appendChild(rankBadge);
        nodeDetails.appendChild(investmentBadge);
        nodeDetails.appendChild(referralsBadge);

        nodeInfo.appendChild(nodeName);
        nodeInfo.appendChild(nodeDetails);
        nodeContent.appendChild(nodeInfo);

        // Click event for node details
        nodeContent.addEventListener('click', () => {
            this.showNodeDetails(nodeData);
        });

        treeNode.appendChild(nodeContent);

        // Create children container
        if (hasChildren) {
            const childrenContainer = document.createElement('div');
            childrenContainer.className = 'children-container';

            nodeData.children.forEach(child => {
                const childNode = this.createTreeNode(child, level + 1);
                childrenContainer.appendChild(childNode);
            });

            treeNode.appendChild(childrenContainer);
        }

        return treeNode;
    }

    toggleNode(treeNode) {
        const expandBtn = treeNode.querySelector('.expand-btn');
        const childrenContainer = treeNode.querySelector('.children-container');

        if (childrenContainer) {
            const isExpanded = childrenContainer.classList.contains('expanded');

            if (isExpanded) {
                childrenContainer.classList.remove('expanded');
                expandBtn.classList.remove('expanded');
            } else {
                childrenContainer.classList.add('expanded');
                expandBtn.classList.add('expanded');
            }
        }
    }

    expandAll() {
        const expandBtns = document.querySelectorAll('.expand-btn');
        const childrenContainers = document.querySelectorAll('.children-container');

        expandBtns.forEach(btn => btn.classList.add('expanded'));
        childrenContainers.forEach(container => container.classList.add('expanded'));
    }

    collapseAll() {
        const expandBtns = document.querySelectorAll('.expand-btn');
        const childrenContainers = document.querySelectorAll('.children-container');

        expandBtns.forEach(btn => btn.classList.remove('expanded'));
        childrenContainers.forEach(container => container.classList.remove('expanded'));
    }

    calculateStats(node, depth = 0) {
        this.stats.totalMembers++;
        this.stats.totalInvestment += parseFloat(node.investment || 0);
        this.stats.maxDepth = Math.max(this.stats.maxDepth, depth);

        if (depth === 1) {
            this.stats.directReferrals++;
        }

        if (node.children) {
            node.children.forEach(child => {
                this.calculateStats(child, depth + 1);
            });
        }
    }

    updateStats() {
        // Reset stats
        this.stats = { totalMembers: 0, directReferrals: 0, maxDepth: 0, totalInvestment: 0 };

        if (this.treeData) {
            this.calculateStats(this.treeData);
        }

        document.getElementById('totalMembers').textContent = this.stats.totalMembers;
        document.getElementById('directReferrals').textContent = this.stats.directReferrals;
        document.getElementById('maxDepth').textContent = this.stats.maxDepth;
        document.getElementById('totalInvestment').textContent = `$${this.stats.totalInvestment.toFixed(2)}`;

        document.getElementById('statsRow').style.display = 'flex';
    }

    setupEventListeners() {
        document.getElementById('expandAll').addEventListener('click', () => {
            this.expandAll();
        });

        document.getElementById('collapseAll').addEventListener('click', () => {
            this.collapseAll();
        });
    }

    showNodeDetails(nodeData) {
        const details = `
User: ${nodeData.name || nodeData.label || 'Unknown'}
Email: ${nodeData.email || 'N/A'}
Rank: ${nodeData.rank || 'Guest'}
Investment: $${nodeData.investment || 0}
Direct Referrals: ${nodeData.referrals_count || 0}
Join Date: ${nodeData.created_at ? new Date(nodeData.created_at).toLocaleDateString() : 'N/A'}
        `.trim();

        alert(details);
    }

    showTree() {
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('treeView').style.display = 'block';
    }

    showError() {
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('errorView').style.display = 'block';
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    new ModernGenealogyTree();
});
</script>
@endsection
