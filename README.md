# HMDG Website Development AI

An AI-powered toolkit for automating the full website development lifecycle — from planning and design to SEO, marketing, and ongoing support.

---

## Project Overview

This project is structured in 5 phases, each targeting a specific stage of the website development and automation process.

---

## Phases

### Phase 1 — Site Planner

**HMDG Site Planner** is an AI-powered tool that generates a sitemap and wireframe based on the client's prompt. It stops at planning — no website building.

#### Completed Tasks
- [x] Site Planner Questionnaires
- [x] Generate Sitemap based on the Questionnaires
- [x] Generate Wireframe based on the Sitemap
- [x] Generate Onboarding for Website AI Automation
- [x] Client Approval Flow (Yes / No validation)
- [x] Email Sending to Project Manager and Client

#### How It Works

The Site Planner follows a strict 5-screen flow:

1. **Hero** — Client describes their project and clicks Generate
2. **Questionnaire** — Client fills in 17 fields across 5 sections
3. **Results** — AI generates a structured plan with 4 tabs: Project Brief, Sitemap, Wireframes, and Dev Notes
4. **Client Approval** — Client reviews and either approves or requests changes (with revision + regenerate)
5. **Send to PM** — Approved plan is emailed to the HMDG Project Manager and CC'd to the client

#### Site Planner Questions

The following information is collected from the client to generate the sitemap and wireframe:

- Client or Business Name
- Client Email
- Business Type
- Business Location
- Services
- Goals
- Target Audience
- Features
- Tone and Voice
- Brand Style
- Showcase / Portfolio
- Integrations
- Competitor List
- Hosting Platform
- Site Language
- Project Description
- Additional Requirements

#### AI Output Structure

The AI returns a structured JSON plan containing:

- **Project Brief** — Business overview, target audience, goals, UVP, tone, features, and recommended pages
- **Sitemap** — SEO-friendly hierarchical page structure with slugs and purpose
- **Wireframes** — Per-page section breakdown with key messages and CTAs
- **Dev Notes** — Content strategy, SEO focus, integrations, and conversion priorities

---

### Next Goal

The following features are planned for Phase 1 and are pending implementation. **Do not implement until instructed.**

- [ ] Create WordPress Plugin Dashboard for Project Manager
- [ ] Store Site Planner Results in Database
- [ ] Display Client Table (Data Table with search function)
- [ ] Assign Designer to Project
- [ ] Send Email Notification to Assigned Designer
- [ ] Add Project Status Indicator (Started, Not Started, Completed)

---

#### Feature Requirements (Documentation Only)

---

##### 1. Project Manager Dashboard (WordPress)

A custom admin dashboard page inside the WordPress plugin, accessible only by users with the Project Manager role. It displays all completed Site Planner submissions received from clients.

---

##### 2. Client Table (Data Table)

A fast, searchable data table displayed in the Project Manager Dashboard.

**Columns:**

| Column | Description |
|---|---|
| Client Name | The business or client name from the questionnaire |
| Client Email | Email address provided by the client |
| Site Planner Result | Expandable view of the Brief, Sitemap, and Wireframe |

**Requirements:**

- Search function to filter clients by name or email
- Fast loading — must not slow down WordPress admin
- Vanilla JavaScript only — no jQuery
- Paginated if the list grows large

---

##### 3. Designer Assignment

The Project Manager can assign a designer to each project directly from the Client Table.

**Available Designers:**

| Name | Email |
|---|---|
| Felmerald | felmerald@hmdg.co.uk |
| Larry | larry@hmdg.co.uk |
| Junemark | junemark@hmdg.co.uk |
| Princess | princess@hmdg.co.uk |
| Antonio | antonio@hmdg.co.uk |
| Mondred | mondred@hmdg.co.uk |
| JhayR | renato@hmdg.co.uk |
| Dannis | dannis@hmdg.co.uk |
| Joshua | joshua@hmdg.co.uk |

The assignment is made via a dropdown selector in the Client Table row.

---

##### 4. Email Notification to Designer

Once a designer is assigned, an automatic email is sent to the assigned designer. The email includes:

- Client Name
- Project Brief summary
- Sitemap overview
- Wireframe overview
- Any notes from the Project Manager

---

##### 5. Project Status

Each project must have a status indicator visible in the Client Table.

| Status | Description |
|---|---|
| Not Started | Default state before a designer is assigned |
| Started | Automatically set when a designer is assigned |
| Completed | Manually set by the Project Manager only |

**Rules:**

- When a designer is assigned → status automatically changes to **Started**
- **Completed** can only be set manually by the Project Manager
- Status cannot go backwards (e.g. Completed cannot revert to Started)

---

### Phase 2 — Website AI Automation
*Coming soon*

---

### Phase 3 — SEO AI Automation
*Coming soon*

---

### Phase 4 — Marketing Consent + Clinik Integration
*Coming soon*

---

### Phase 5 — Website Support Automation
*Coming soon*

---

## Tech Stack

| Layer | Technology |
|---|---|
| Platform | WordPress Plugin (PHP 8.0+) |
| Frontend | Bootstrap 5, Vanilla JavaScript (no jQuery) |
| Typography | Cabinet Grotesk (headings), Satoshi (body) |
| AI Providers | Anthropic Claude / OpenAI GPT (configurable) |
| Email | WordPress `wp_mail()` |
| Shortcode | `[hmdg-website-development-ai]` |

---

## Getting Started

### Shortcode Usage

Create any WordPress page or post and add the following shortcode:

```
[hmdg-website-development-ai]
```

The plugin will render the full branded UI on that page. Assets (Bootstrap, fonts, CSS, JS) only load on pages where the shortcode is actually present.

### Admin Settings

Go to **HMDG Planner → Settings** in the WordPress admin to configure:

- AI Provider (Claude or OpenAI)
- API Key
- Model selection
- Max tokens

Use the **Test API Connection** button to verify your key before use.

---

*Developed by HMDG*
